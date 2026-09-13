<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminPenggunaController extends Controller
{
    /**
     * Tampilkan halaman daftar data pengguna (pelanggan).
     */
    public function index(Request $request): View
    {
        $search = trim($request->input('search', ''));
        $filterStatus = $request->input('status', 'all');

        // Query hanya pelanggan (kecuali admin dan karyawan)
        $query = User::whereNotIn('role', ['admin', 'karyawan'])
            ->withCount('pesanan')
            ->withSum('pesanan', 'total_harga');

        // Filter tab status / tipe
        if ($filterStatus === 'aktif') {
            $query->has('pesanan');
        } elseif ($filterStatus === 'nonaktif') {
            $query->doesntHave('pesanan');
        } elseif ($filterStatus === 'siswa') {
            $query->whereNotNull('kelas')->where('kelas', '!=', '-');
        } elseif ($filterStatus === 'guru_umum') {
            $query->where(function ($q) {
                $q->whereNull('kelas')->orWhere('kelas', '-');
            });
        }

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $penggunas = $query->orderBy('id_user', 'desc')->paginate(10)->withQueryString();

        // Hitung statistik ringkasan kartu atas
        $totalPengguna = User::whereNotIn('role', ['admin', 'karyawan'])->count();
        $penggunaAktif = User::whereNotIn('role', ['admin', 'karyawan'])->has('pesanan')->count();
        $penggunaNonAktif = $totalPengguna - $penggunaAktif;

        $totalSiswa = User::whereNotIn('role', ['admin', 'karyawan'])
            ->whereNotNull('kelas')
            ->where('kelas', '!=', '-')
            ->count();

        $totalGuruUmum = User::whereNotIn('role', ['admin', 'karyawan'])
            ->where(function ($q) {
                $q->whereNull('kelas')->orWhere('kelas', '-');
            })
            ->count();

        $persenAktif = $totalPengguna > 0 ? round(($penggunaAktif / $totalPengguna) * 100, 1) : 0;

        return view('admin.pengguna.index', compact(
            'penggunas',
            'search',
            'filterStatus',
            'totalPengguna',
            'penggunaAktif',
            'penggunaNonAktif',
            'totalSiswa',
            'totalGuruUmum',
            'persenAktif'
        ));
    }

    /**
     * Dapatkan detail lengkap pengguna dalam format JSON untuk modal preview.
     */
    public function show(User $pengguna): JsonResponse
    {
        if (in_array($pengguna->role, ['admin', 'karyawan'])) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        $pengguna->load(['pesanan' => function ($q) {
            $q->latest('tanggal_pesan')->take(5);
        }, 'pesanan.detailPesanan.menu', 'pesanan.pembayaran']);

        $totalPesanan = $pengguna->pesanan()->count();
        $totalBelanja = (float) $pengguna->pesanan()->sum('total_harga');

        $tipeAkun = (! empty($pengguna->kelas) && $pengguna->kelas !== '-') ? 'Siswa' : 'Guru / Umum';

        return response()->json([
            'success' => true,
            'user' => [
                'id_user' => $pengguna->id_user,
                'formatted_id' => $pengguna->formatted_id,
                'nama' => $pengguna->nama,
                'email' => $pengguna->email,
                'no_hp' => $pengguna->no_hp,
                'nis' => $pengguna->nis ?? '-',
                'kelas' => $pengguna->kelas ?? '-',
                'jenis_kelamin' => $pengguna->jenis_kelamin_label,
                'jenis_kelamin_raw' => $pengguna->jenis_kelamin,
                'alamat' => $pengguna->alamat ?? '-',
                'tipe_akun' => $tipeAkun,
                'foto_identitas' => $pengguna->foto_identitas_url,
                'created_at' => $pengguna->created_at ? $pengguna->created_at->translatedFormat('d F Y') : '-',
                'total_pesanan' => $totalPesanan,
                'total_belanja' => 'Rp'.number_format($totalBelanja, 0, ',', '.'),
                'is_aktif' => $totalPesanan > 0,
                'pesanan_terbaru' => $pengguna->pesanan->map(function ($p) {
                    return [
                        'order_number' => $p->order_number,
                        'tanggal' => $p->tanggal_pesan ? $p->tanggal_pesan->translatedFormat('d M Y, H:i') : '-',
                        'total' => 'Rp'.number_format((float) $p->total_harga, 0, ',', '.'),
                        'status' => ucfirst(str_replace('_', ' ', $p->status_pesanan)),
                        'items' => $p->items_summary,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Simpan data pengguna baru dari admin.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6',
            'no_hp' => 'required|string|max:20',
            'nis' => 'nullable|string|max:30|unique:users,nis',
            'kelas' => 'nullable|string|max:50',
            'jenis_kelamin' => 'required|string|in:L,P',
            'alamat' => 'nullable|string|max:500',
            'foto_identitas' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_identitas')) {
            $file = $request->file('foto_identitas');
            $extension = $file->getClientOriginalExtension();
            $filename = 'identitas_'.($validated['nis'] ?? time()).'_'.time().'.'.$extension;
            $fotoPath = $file->storeAs('uploads/identitas', $filename, 'public');
        }

        User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'no_hp' => $validated['no_hp'],
            'nis' => $validated['nis'] ?: null,
            'kelas' => $validated['kelas'] ?: '-',
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'role' => 'pelanggan',
            'alamat' => $validated['alamat'] ?: '-',
            'foto_identitas' => $fotoPath,
        ]);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Update data pengguna dari admin.
     */
    public function update(Request $request, User $pengguna): RedirectResponse
    {
        if (in_array($pengguna->role, ['admin', 'karyawan'])) {
            abort(403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($pengguna->id_user, 'id_user')],
            'password' => 'nullable|string|min:6',
            'no_hp' => 'required|string|max:20',
            'nis' => ['nullable', 'string', 'max:30', Rule::unique('users', 'nis')->ignore($pengguna->id_user, 'id_user')],
            'kelas' => 'nullable|string|max:50',
            'jenis_kelamin' => 'required|string|in:L,P',
            'alamat' => 'nullable|string|max:500',
            'foto_identitas' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $updateData = [
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
            'nis' => $validated['nis'] ?: null,
            'kelas' => $validated['kelas'] ?: '-',
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat' => $validated['alamat'] ?: '-',
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        if ($request->hasFile('foto_identitas')) {
            if ($pengguna->foto_identitas && Storage::disk('public')->exists($pengguna->foto_identitas)) {
                Storage::disk('public')->delete($pengguna->foto_identitas);
            }
            $file = $request->file('foto_identitas');
            $extension = $file->getClientOriginalExtension();
            $filename = 'identitas_'.($validated['nis'] ?? time()).'_'.time().'.'.$extension;
            $updateData['foto_identitas'] = $file->storeAs('uploads/identitas', $filename, 'public');
        }

        $pengguna->update($updateData);

        return redirect()->route('admin.pengguna.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Hapus akun pengguna.
     */
    public function destroy(User $pengguna): RedirectResponse
    {
        if (in_array($pengguna->role, ['admin', 'karyawan'])) {
            abort(403);
        }

        if ($pengguna->foto_identitas && Storage::disk('public')->exists($pengguna->foto_identitas)) {
            Storage::disk('public')->delete($pengguna->foto_identitas);
        }

        $nama = $pengguna->nama;
        $pengguna->delete();

        return redirect()->route('admin.pengguna.index')->with('success', "Akun pengguna {$nama} berhasil dihapus.");
    }

    /**
     * Ekspor data pengguna ke CSV format.
     */
    public function exportCsv(): StreamedResponse
    {
        $users = User::whereNotIn('role', ['admin', 'karyawan'])
            ->withCount('pesanan')
            ->withSum('pesanan', 'total_harga')
            ->orderBy('id_user', 'asc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="data_pengguna_koteshop_'.date('Ymd_His').'.csv"',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'ID Pelanggan',
                'Nama Lengkap',
                'Email',
                'No. HP',
                'NIS',
                'Kelas / Jurusan',
                'Jenis Kelamin',
                'Alamat',
                'Total Pesanan',
                'Total Belanja (Rp)',
                'Tanggal Daftar',
            ]);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->formatted_id,
                    $user->nama,
                    $user->email,
                    $user->no_hp,
                    $user->nis ?? '-',
                    $user->kelas ?? '-',
                    $user->jenis_kelamin_label,
                    $user->alamat ?? '-',
                    $user->pesanan_count,
                    $user->pesanan_sum_total_harga ?? 0,
                    $user->created_at ? $user->created_at->format('Y-m-d H:i') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
