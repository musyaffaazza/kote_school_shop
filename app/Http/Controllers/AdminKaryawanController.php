<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminKaryawanController extends Controller
{
    /**
     * Tampilkan daftar karyawan dengan filter role dan pencarian.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search', '');
        $filterJabatan = $request->input('jabatan', 'all');

        $query = User::where('role', 'karyawan');

        if ($filterJabatan !== 'all') {
            $query->where('jabatan', $filterJabatan);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $karyawans = $query->orderBy('id_user', 'desc')->paginate(10)->withQueryString();

        return view('admin.karyawan.index', compact('karyawans', 'search', 'filterJabatan'));
    }

    /**
     * Tampilkan form tambah karyawan.
     */
    public function create(): View
    {
        return view('admin.karyawan.create');
    }

    /**
     * Simpan karyawan baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6',
            'no_hp' => 'required|string|max:15',
            'jabatan' => 'required|string|in:Barista,Kasir',
            'jenis_kelamin' => 'required|string|in:L,P',
            'alamat' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'no_hp' => $validated['no_hp'],
            'nis' => 'KRY'.str_pad(random_int(0, 9999999), 7, '0', STR_PAD_LEFT),
            'kelas' => '-',
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'role' => 'karyawan',
            'jabatan' => $validated['jabatan'],
            'alamat' => $validated['alamat'],
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $ext = $file->getClientOriginalExtension();
            $path = $file->storeAs('karyawan/'.$user->id_user, 'foto.'.$ext, 'public');
            $user->foto_identitas = '/storage/'.$path;
            $user->save();
        }

        return redirect()->route('admin.karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit karyawan.
     */
    public function edit(User $karyawan): View
    {
        return view('admin.karyawan.edit', compact('karyawan'));
    }

    /**
     * Update data karyawan di database.
     */
    public function update(Request $request, User $karyawan): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($karyawan->id_user, 'id_user')],
            'password' => 'nullable|string|min:6',
            'no_hp' => 'required|string|max:15',
            'jabatan' => 'required|string|in:Barista,Kasir',
            'jenis_kelamin' => 'required|string|in:L,P',
            'alamat' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $karyawan->nama = $validated['nama'];
        $karyawan->email = $validated['email'];
        $karyawan->no_hp = $validated['no_hp'];
        $karyawan->jabatan = $validated['jabatan'];
        $karyawan->jenis_kelamin = $validated['jenis_kelamin'];
        $karyawan->alamat = $validated['alamat'];

        if (! empty($validated['password'])) {
            $karyawan->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($karyawan->foto_identitas && str_starts_with($karyawan->foto_identitas, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $karyawan->foto_identitas);
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('foto');
            $ext = $file->getClientOriginalExtension();
            $path = $file->storeAs('karyawan/'.$karyawan->id_user, 'foto.'.$ext, 'public');
            $karyawan->foto_identitas = '/storage/'.$path;
        }

        $karyawan->save();

        return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Hapus karyawan dari database.
     */
    public function destroy(User $karyawan): RedirectResponse
    {
        // Hapus folder foto
        Storage::disk('public')->deleteDirectory('karyawan/'.$karyawan->id_user);

        $karyawan->delete();

        return redirect()->route('admin.karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
