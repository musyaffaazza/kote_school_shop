<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminPromoController extends Controller
{
    /**
     * Tampilkan daftar promo.
     */
    public function index(Request $request): View
    {
        $query = Promo::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama_promo', 'like', "%{$search}%")
                ->orWhere('kode_voucher', 'like', "%{$search}%");
        }

        $promos = $query->latest()->paginate(3)->withQueryString();

        return view('admin.promo.index', [
            'promos' => $promos,
            'search' => $request->input('search'),
        ]);
    }

    /**
     * Tampilkan form tambah promo baru.
     */
    public function create(): View
    {
        return view('admin.promo.create');
    }

    /**
     * Simpan promo baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_promo' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'jenis_promo' => 'required|string|in:diskon,paket,voucher',
            'nilai_promo' => 'required|integer|min:0',
            'satuan_nilai' => 'required|string|in:rupiah,persen',
            'kode_voucher' => 'nullable|string|max:50|unique:promos,kode_voucher',
            'periode_mulai' => 'nullable|date',
            'periode_selesai' => 'nullable|date|after_or_equal:periode_mulai',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if (! empty($validated['kode_voucher'])) {
            $validated['kode_voucher'] = strtoupper($validated['kode_voucher']);
        }

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $path = $file->store('promos', 'public');
            $validated['gambar'] = '/storage/'.$path;
        }

        Promo::create($validated);

        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit promo.
     */
    public function edit(Promo $promo): View
    {
        return view('admin.promo.edit', compact('promo'));
    }

    /**
     * Update data promo di database.
     */
    public function update(Request $request, Promo $promo): RedirectResponse
    {
        $validated = $request->validate([
            'nama_promo' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'jenis_promo' => 'required|string|in:diskon,paket,voucher',
            'nilai_promo' => 'required|integer|min:0',
            'satuan_nilai' => 'required|string|in:rupiah,persen',
            'kode_voucher' => 'nullable|string|max:50|unique:promos,kode_voucher,'.$promo->id,
            'periode_mulai' => 'nullable|date',
            'periode_selesai' => 'nullable|date|after_or_equal:periode_mulai',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if (! empty($validated['kode_voucher'])) {
            $validated['kode_voucher'] = strtoupper($validated['kode_voucher']);
        }

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($promo->gambar && str_starts_with($promo->gambar, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $promo->gambar);
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('gambar');
            $path = $file->store('promos', 'public');
            $validated['gambar'] = '/storage/'.$path;
        } else {
            $validated['gambar'] = $promo->gambar;
        }

        $promo->update($validated);

        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil diperbarui.');
    }

    /**
     * Hapus promo dari database.
     */
    public function destroy(Promo $promo): RedirectResponse
    {
        if ($promo->gambar && str_starts_with($promo->gambar, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $promo->gambar);
            Storage::disk('public')->delete($oldPath);
        }

        $promo->delete();

        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil dihapus.');
    }

    /**
     * Toggle status aktif promo.
     */
    public function toggleStatus(Promo $promo): RedirectResponse
    {
        $promo->update(['is_active' => ! $promo->is_active]);

        $status = $promo->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Promo berhasil {$status}.");
    }
}
