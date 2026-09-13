<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Stok;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminMenuController extends Controller
{
    /**
     * Tampilkan daftar menu
     */
    public function index(Request $request): View
    {
        $query = Menu::query();

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama_menu', 'like', "%{$search}%")
                ->orWhere('kategori', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%");
        }

        // Filter kategori
        if ($request->filled('kategori') && $request->input('kategori') !== 'all') {
            $query->where('kategori', $request->input('kategori'));
        }

        $menus = $query->orderBy('nama_menu')->paginate(10)->withQueryString();

        // Dapatkan kategori unik untuk filter dropdown
        $categories = Menu::select('kategori')->distinct()->pluck('kategori');

        return view('admin.menu.index', [
            'menus' => $menus,
            'categories' => $categories,
            'filterCategory' => $request->input('kategori', 'all'),
            'search' => $request->input('search'),
        ]);
    }

    /**
     * Tampilkan form tambah menu
     */
    public function create(): View
    {
        return view('admin.menu.create');
    }

    /**
     * Simpan menu baru ke database
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_menu' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'required|image|mimes:jpg,jpeg,png,webp',
            'gambar_sub_1' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'gambar_sub_2' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'gambar_sub_3' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'status' => 'required|string|in:tersedia,habis',
        ]);

        if ($validated['stok'] === 0) {
            $validated['status'] = 'habis';
        }

        // Simpan data menu tanpa gambar terlebih dahulu untuk mendapatkan ID
        $menu = Menu::create([
            'nama_menu' => $validated['nama_menu'],
            'kategori' => $validated['kategori'],
            'harga' => $validated['harga'],
            'stok' => $validated['stok'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'status' => $validated['status'],
            'gambar' => '', // Sementara kosong
        ]);

        // Catat stok awal ke tabel stok
        Stok::create([
            'id_menu' => $menu->id_menu,
            'stok_masuk' => $menu->stok,
            'stok_keluar' => 0,
            'stok_tersedia' => $menu->stok,
            'tanggal_update' => now(),
        ]);

        $folder = 'menu/'.$menu->id_menu;

        // Simpan gambar utama
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $ext = $file->getClientOriginalExtension();
            $path = $file->storeAs($folder, 'main.'.$ext, 'public');
            $menu->gambar = '/storage/'.$path;
            $menu->save();
        }

        // Simpan sub-gambar jika diunggah
        foreach (['gambar_sub_1' => 'sub_1', 'gambar_sub_2' => 'sub_2', 'gambar_sub_3' => 'sub_3'] as $inputKey => $fileName) {
            if ($request->hasFile($inputKey)) {
                $file = $request->file($inputKey);
                $ext = $file->getClientOriginalExtension();
                $file->storeAs($folder, $fileName.'.'.$ext, 'public');
            }
        }

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit menu
     */
    public function edit(Menu $menu): View
    {
        return view('admin.menu.edit', compact('menu'));
    }

    /**
     * Update data menu di database
     */
    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'nama_menu' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'gambar_sub_1' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'gambar_sub_2' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'gambar_sub_3' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'status' => 'required|string|in:tersedia,habis',
        ]);

        if ($validated['stok'] === 0) {
            $validated['status'] = 'habis';
        }

        $oldStock = (int) $menu->stok;
        $newStock = (int) $validated['stok'];
        $diff = $newStock - $oldStock;

        $folder = 'menu/'.$menu->id_menu;

        // Update gambar utama jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar utama lama jika ada
            if ($menu->gambar && str_starts_with($menu->gambar, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $menu->gambar);
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('gambar');
            $ext = $file->getClientOriginalExtension();
            $path = $file->storeAs($folder, 'main.'.$ext, 'public');
            $validated['gambar'] = '/storage/'.$path;
        } else {
            $validated['gambar'] = $menu->gambar;
        }

        // Update sub-gambar jika diunggah
        foreach (['gambar_sub_1' => 'sub_1', 'gambar_sub_2' => 'sub_2', 'gambar_sub_3' => 'sub_3'] as $inputKey => $fileName) {
            if ($request->hasFile($inputKey)) {
                // Hapus sub-gambar lama dengan nama yang sama tapi ekstensi berbeda
                $existingFiles = Storage::disk('public')->files($folder);
                foreach ($existingFiles as $exFile) {
                    $baseName = pathinfo($exFile, PATHINFO_FILENAME);
                    if ($baseName === $fileName) {
                        Storage::disk('public')->delete($exFile);
                    }
                }

                $file = $request->file($inputKey);
                $ext = $file->getClientOriginalExtension();
                $file->storeAs($folder, $fileName.'.'.$ext, 'public');
            }
        }

        $menu->update([
            'nama_menu' => $validated['nama_menu'],
            'kategori' => $validated['kategori'],
            'harga' => $validated['harga'],
            'stok' => $validated['stok'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'gambar' => $validated['gambar'],
            'status' => $validated['status'],
        ]);

        // Catat mutasi stok ke tabel stok jika ada perubahan jumlah
        if ($diff !== 0) {
            Stok::create([
                'id_menu' => $menu->id_menu,
                'stok_masuk' => $diff > 0 ? $diff : 0,
                'stok_keluar' => $diff < 0 ? abs($diff) : 0,
                'stok_tersedia' => $newStock,
                'tanggal_update' => now(),
            ]);
        }

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui.');
    }

    /**
     * Hapus menu dari database
     */
    public function destroy(Menu $menu): RedirectResponse
    {
        $folder = 'menu/'.$menu->id_menu;

        // Hapus seluruh folder menu ini
        Storage::disk('public')->deleteDirectory($folder);

        // Hapus jika ada path lama di luar folder menu/{id_menu}
        if ($menu->gambar && str_starts_with($menu->gambar, '/storage/') && ! str_contains($menu->gambar, "/menu/{$menu->id_menu}/")) {
            $oldPath = str_replace('/storage/', '', $menu->gambar);
            Storage::disk('public')->delete($oldPath);
        }

        $menu->delete();

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus.');
    }
}
