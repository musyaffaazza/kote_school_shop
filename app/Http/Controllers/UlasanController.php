<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Ulasan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UlasanController extends Controller
{
    /**
     * Tampilkan halaman formulir ulasan.
     */
    public function create(?Pesanan $pesanan = null): View
    {
        $user = auth()->user();

        if ($pesanan) {
            if ($pesanan->id_user !== $user->id_user) {
                abort(403);
            }
            $pesanan->load(['detailPesanan.menu']);
        } else {
            $pesanan = Pesanan::with(['detailPesanan.menu'])
                ->where('id_user', $user->id_user)
                ->orderByDesc('tanggal_pesan')
                ->first();
        }

        $menus = $pesanan ? $pesanan->detailPesanan->pluck('menu')->filter()->unique('id_menu')->values() : collect();

        if ($menus->isEmpty()) {
            $menus = Menu::whereIn('nama_menu', ['Aren Latte', 'Americano'])->get();
            if ($menus->isEmpty()) {
                $menus = Menu::take(2)->get();
            }
        }

        // Check if this specific order has already been reviewed
        $sudahDiulas = false;
        if ($pesanan) {
            $sudahDiulas = Ulasan::where('id_pesanan', $pesanan->id_pesanan)
                ->where('status', 'aktif')
                ->exists();
        }

        return view('ulasan.create', compact('pesanan', 'menus', 'sudahDiulas'));
    }

    /**
     * Simpan data ulasan pelanggan ke database.
     */
    public function store(Request $request, ?Pesanan $pesanan = null): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|min:3|max:1000',
            'menu_ids' => 'nullable|array',
            'menu_ids.*' => 'integer|exists:menu,id_menu',
        ]);

        $user = auth()->user();

        if ($pesanan && $pesanan->id_user !== $user->id_user) {
            abort(403);
        }

        $menuIds = $validated['menu_ids'] ?? [];

        if (empty($menuIds) && $pesanan) {
            $menuIds = $pesanan->detailPesanan->pluck('id_menu')->unique()->toArray();
        }

        if (empty($menuIds)) {
            $firstMenu = Menu::first();
            if ($firstMenu) {
                $menuIds = [$firstMenu->id_menu];
            }
        }

        // Use the first menu_id for primary review association
        $primaryMenuId = reset($menuIds) ?: null;

        if ($primaryMenuId) {
            // Prevent duplicate review for the same specific order
            if ($pesanan) {
                $alreadyReviewed = Ulasan::where('id_pesanan', $pesanan->id_pesanan)
                    ->where('status', 'aktif')
                    ->exists();

                if ($alreadyReviewed) {
                    return redirect()->to(route('home').'#ulasan')->with('success', 'Pesanan ini sudah pernah Anda beri ulasan.');
                }
            }

            Ulasan::create([
                'id_user' => $user->id_user,
                'id_pesanan' => $pesanan?->id_pesanan,
                'id_menu' => $primaryMenuId,
                'rating' => $validated['rating'],
                'komentar' => $validated['komentar'],
                'tanggal_ulasan' => now(),
                'status' => 'aktif',
            ]);
        }

        return redirect()->to(route('home').'#ulasan')->with('success', 'Terima kasih atas ulasan Anda! Masukan Anda telah ditampilkan di Beranda.');
    }
}
