<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Menu;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Promo;
use App\Models\Ulasan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index()
    {
        $favoritMenu = Menu::where('status', 'tersedia')
            ->where('stok', '>', 0)
            ->orderByDesc('stok')
            ->take(4)
            ->get();

        // Fallback jika menu dengan stok > 0 belum cukup 4 item
        if ($favoritMenu->count() < 4) {
            $favoritMenu = Menu::take(4)->get();
        }

        $ulasanList = [];
        $avgRating = '0.0';
        $totalUlasan = '0';

        if (Schema::hasTable('ulasan')) {
            $totalCount = Ulasan::where('status', 'aktif')->count();
            $totalUlasan = number_format($totalCount);

            if ($totalCount > 0) {
                $avgRating = number_format((float) Ulasan::where('status', 'aktif')->avg('rating'), 1);
            }

            $dbUlasans = Ulasan::with(['user', 'menu'])
                ->where('status', 'aktif')
                ->orderByDesc('tanggal_ulasan')
                ->orderByDesc('id_ulasan')
                ->get();

            $ulasanList = $dbUlasans->map(function ($u) {
                $nama = $u->user ? $u->user->nama : 'Pelanggan Kote';

                return [
                    'nama' => $nama,
                    'waktu' => $u->tanggal_ulasan ? $u->tanggal_ulasan->diffForHumans() : 'Baru saja',
                    'avatar' => mb_strtoupper(mb_substr($nama, 0, 1)),
                    'rating' => (int) $u->rating,
                    'menu' => $u->menu ? $u->menu->nama_menu : 'Menu Kote',
                    'teks' => $u->komentar,
                    'balasan' => $u->balasan,
                ];
            })->toArray();
        }

        return view('home', compact('favoritMenu', 'ulasanList', 'avgRating', 'totalUlasan'));
    }

    /**
     * Tampilkan halaman Menu
     */
    public function menu(Request $request): View
    {
        $menus = Menu::all();

        return view('menu', compact('menus'));
    }

    /**
     * Tampilkan halaman detail Menu
     */
    public function menuShow(Menu $menu): View
    {
        return view('menu-show', compact('menu'));
    }

    /**
     * Tampilkan halaman Promo
     */
    public function promo(): View
    {
        $promos = Promo::active()
            ->valid()
            ->latest()
            ->get();

        return view('promo', compact('promos'));
    }

    /**
     * Cek validitas kode promo / voucher untuk checkout.
     */
    public function checkPromo(Request $request): JsonResponse
    {
        $request->validate([
            'kode' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $code = strtoupper(trim($request->input('kode')));
        $subtotal = (float) $request->input('subtotal');

        $promo = Promo::active()
            ->valid()
            ->where('kode_voucher', $code)
            ->first();

        if (! $promo) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak valid atau sudah tidak berlaku.',
            ], 422);
        }

        $discount = 0;
        if ($promo->satuan_nilai === 'persen') {
            $discount = (int) round(($subtotal * $promo->nilai_promo) / 100);
        } else {
            $discount = min((int) $subtotal, (int) $promo->nilai_promo);
        }

        return response()->json([
            'success' => true,
            'promo' => [
                'id' => $promo->id,
                'nama' => $promo->nama_promo,
                'kode' => $promo->kode_voucher,
                'nilai' => $promo->nilai_promo,
                'satuan' => $promo->satuan_nilai,
                'formatted' => $promo->nilai_formatted,
            ],
            'discount' => $discount,
            'message' => "Kode promo {$promo->kode_voucher} berhasil diterapkan! Diskon {$promo->nilai_formatted}.",
        ]);
    }

    /**
     * Tampilkan halaman Tentang Kami
     */
    public function about(): View
    {
        return view('about');
    }

    /**
     * Tampilkan halaman Kontak (Hubungi Kami)
     */
    public function contact(): View
    {
        return view('contact');
    }

    /**
     * Tampilkan halaman Keranjang Belanja
     */
    public function cart(): View
    {
        return view('cart');
    }

    /**
     * Tampilkan halaman Checkout
     */
    public function checkout(): View
    {
        $paymentSettings = Pembayaran::getSettings();

        return view('checkout', compact('paymentSettings'));
    }

    public function payment(?Pesanan $pesanan = null): View
    {
        $paymentSettings = Pembayaran::getSettings();

        if ($pesanan && $pesanan->exists) {
            if (auth()->check() && auth()->user()->role === 'pelanggan' && $pesanan->id_user !== auth()->user()->id_user) {
                abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
            }

            $pesanan->load(['detailPesanan.menu', 'pembayaran']);

            return view('payment', compact('pesanan', 'paymentSettings'));
        }

        // Fallback: Try to find the latest order for the authenticated user that is pending payment
        if (auth()->check()) {
            $pesanan = Pesanan::with(['detailPesanan.menu', 'pembayaran'])
                ->where('id_user', auth()->user()->id_user)
                ->whereHas('pembayaran', function ($query) {
                    $query->where('status', 'menunggu');
                })
                ->orderByDesc('tanggal_pesan')
                ->first();

            if ($pesanan) {
                return view('payment', compact('pesanan', 'paymentSettings'));
            }
        }

        return view('payment', compact('paymentSettings'));
    }

    /**
     * Tangani pengiriman formulir Kontak
     */
    public function submitContact(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subjek' => ['required', 'string', Rule::in(['Saran / Masukan', 'Kerja Sama'])],
            'pesan' => 'required|string',
        ]);

        ContactMessage::create($request->only(['nama', 'email', 'subjek', 'pesan']));

        return redirect()->route('contact')->with('success', 'Pesan Anda berhasil dikirim! Terima kasih atas masukan Anda.');
    }

    /**
     * Sync guest cart with user cart on login.
     */
    public function syncLoginCart(Request $request): JsonResponse
    {
        $user = $request->user();
        $guestCart = $request->input('cart', []);

        if (! is_array($guestCart)) {
            $guestCart = [];
        }

        $userCart = $user->cart ?? [];

        // Merge carts
        $mergedCart = $this->mergeCarts($userCart, $guestCart);

        $user->cart = $mergedCart;
        $user->save();

        return response()->json([
            'status' => 'success',
            'cart' => $mergedCart,
        ]);
    }

    /**
     * Update user cart in database.
     */
    public function updateCart(Request $request): JsonResponse
    {
        $user = $request->user();
        $cart = $request->input('cart', []);

        if (! is_array($cart)) {
            $cart = [];
        }

        $user->cart = $cart;
        $user->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    /**
     * Merge two carts together.
     */
    private function mergeCarts(array $userCart, array $guestCart): array
    {
        // Helper to generate a unique key for an item based on its ID and customizations
        $getKey = function ($item) {
            $id = $item['id'] ?? '';
            $sugar = $item['sugar'] ?? '';
            $ice = $item['ice'] ?? '';
            $toppings = $item['toppings'] ?? [];
            if (is_string($toppings)) {
                $toppings = json_decode($toppings, true) ?? [];
            }
            sort($toppings);
            $toppingsStr = implode(',', $toppings);
            $notes = $item['notes'] ?? '';

            return "{$id}_{$sugar}_{$ice}_{$toppingsStr}_{$notes}";
        };

        $merged = [];

        // Load user cart items first
        foreach ($userCart as $item) {
            $key = $getKey($item);
            $merged[$key] = $item;
        }

        // Merge guest cart items
        foreach ($guestCart as $item) {
            $key = $getKey($item);
            if (isset($merged[$key])) {
                // If item exists, we add the quantities, maxing out at 99
                $merged[$key]['qty'] = min(99, ($merged[$key]['qty'] ?? 1) + ($item['qty'] ?? 1));
            } else {
                $merged[$key] = $item;
            }
        }

        return array_values($merged);
    }
}
