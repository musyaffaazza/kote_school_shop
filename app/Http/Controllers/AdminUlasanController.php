<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Ulasan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUlasanController extends Controller
{
    /**
     * Tampilkan halaman Review Management untuk Admin.
     */
    public function index(Request $request): View
    {
        // 1. Hitung Statistik Ringkasan
        $totalUlasan = Ulasan::count();
        $avgRatingRaw = $totalUlasan > 0 ? (float) Ulasan::avg('rating') : 0.0;
        $avgRating = number_format($avgRatingRaw, 1);

        $positiveCount = Ulasan::where('rating', '>=', 4)->count();
        $positivePercentage = $totalUlasan > 0 ? round(($positiveCount / $totalUlasan) * 100) : 0;

        // Hitung perbandingan ulasan minggu ini vs minggu lalu
        $thisWeekCount = Ulasan::where('tanggal_ulasan', '>=', now()->startOfWeek())->count();
        $lastWeekCount = Ulasan::whereBetween('tanggal_ulasan', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();
        $growthPercent = $lastWeekCount > 0
            ? round((($thisWeekCount - $lastWeekCount) / $lastWeekCount) * 100)
            : ($thisWeekCount > 0 ? 100 : 0);

        // 2. Query Daftar Ulasan dengan Filter
        $query = Ulasan::with(['user', 'menu']);

        // Search Filter (nama pelanggan, nama menu, komentar ulasan, atau balasan admin)
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('komentar', 'like', "%{$search}%")
                    ->orWhere('balasan', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('menu', function ($mq) use ($search) {
                        $mq->where('nama_menu', 'like', "%{$search}%");
                    });
            });
        }

        // Filter Menu
        if ($request->filled('menu_id') && $request->input('menu_id') !== 'all') {
            $query->where('id_menu', $request->input('menu_id'));
        }

        // Filter Rating Bintang
        if ($request->filled('rating') && $request->input('rating') !== 'all') {
            $query->where('rating', (int) $request->input('rating'));
        }

        // Filter Status Balasan
        if ($request->filled('reply_status') && $request->input('reply_status') !== 'all') {
            if ($request->input('reply_status') === 'sudah_dibalas') {
                $query->whereNotNull('balasan')->where('balasan', '!=', '');
            } elseif ($request->input('reply_status') === 'belum_dibalas') {
                $query->where(function ($q) {
                    $q->whereNull('balasan')->orWhere('balasan', '');
                });
            }
        }

        // Sorting / Urutan
        $sort = $request->input('sort', 'terbaru');
        switch ($sort) {
            case 'terlama':
                $query->orderBy('tanggal_ulasan', 'asc')->orderBy('id_ulasan', 'asc');
                break;
            case 'tertinggi':
                $query->orderByDesc('rating')->orderByDesc('tanggal_ulasan');
                break;
            case 'terendah':
                $query->orderBy('rating', 'asc')->orderByDesc('tanggal_ulasan');
                break;
            case 'terbaru':
            default:
                $query->orderByDesc('tanggal_ulasan')->orderByDesc('id_ulasan');
                break;
        }

        $ulasans = $query->paginate(10)->withQueryString();
        $menus = Menu::orderBy('nama_menu')->get();

        return view('admin.ulasan.index', [
            'ulasans' => $ulasans,
            'menus' => $menus,
            'totalUlasan' => $totalUlasan,
            'avgRating' => $avgRating,
            'positivePercentage' => $positivePercentage,
            'growthPercent' => $growthPercent,
            'sort' => $sort,
            'search' => $request->input('search'),
            'selectedMenu' => $request->input('menu_id', 'all'),
            'selectedRating' => $request->input('rating', 'all'),
            'selectedReplyStatus' => $request->input('reply_status', 'all'),
        ]);
    }

    /**
     * Kirim atau update balasan admin untuk suatu ulasan.
     */
    public function reply(Request $request, Ulasan $ulasan): RedirectResponse
    {
        $validated = $request->validate([
            'balasan' => 'required|string|min:2|max:1000',
        ], [
            'balasan.required' => 'Tuliskan teks balasan terlebih dahulu.',
            'balasan.min' => 'Balasan minimal 2 karakter.',
            'balasan.max' => 'Balasan maksimal 1000 karakter.',
        ]);

        $ulasan->update([
            'balasan' => trim($validated['balasan']),
            'tanggal_balasan' => now(),
        ]);

        return redirect()->back()->with('success', 'Balasan ulasan berhasil disimpan.');
    }

    /**
     * Hapus balasan admin dari suatu ulasan.
     */
    public function destroyReply(Ulasan $ulasan): RedirectResponse
    {
        $ulasan->update([
            'balasan' => null,
            'tanggal_balasan' => null,
        ]);

        return redirect()->back()->with('success', 'Balasan ulasan berhasil dihapus.');
    }

    /**
     * Hapus ulasan pelanggan secara permanen dari database.
     */
    public function destroy(Ulasan $ulasan): RedirectResponse
    {
        $ulasan->delete();

        return redirect()->back()->with('success', 'Ulasan pelanggan berhasil dihapus.');
    }

    /**
     * Toggle status aktif / nonaktif ulasan.
     */
    public function toggleStatus(Ulasan $ulasan): RedirectResponse
    {
        $newStatus = ($ulasan->status === 'aktif') ? 'nonaktif' : 'aktif';
        $ulasan->update(['status' => $newStatus]);

        $label = $newStatus === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Status ulasan berhasil {$label}.");
    }
}
