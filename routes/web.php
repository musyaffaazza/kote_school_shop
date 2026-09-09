<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminKaryawanController;
use App\Http\Controllers\AdminLaporanKeuanganController;
use App\Http\Controllers\AdminMenuController;
use App\Http\Controllers\AdminMessageController;
use App\Http\Controllers\AdminPembayaranController;
use App\Http\Controllers\AdminPromoController;
use App\Http\Controllers\AdminUlasanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KaryawanDashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

// Landing Page (Beranda) - Dapat diakses oleh Guest maupun Auth user
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [HomeController::class, 'menu'])->name('menu');
Route::get('/menu/{menu}', [HomeController::class, 'menuShow'])->name('menu.show');
Route::get('/promo', [HomeController::class, 'promo'])->name('promo');
Route::post('/promo/check', [HomeController::class, 'checkPromo'])->name('promo.check');
Route::get('/tentang-kami', [HomeController::class, 'about'])->name('about');
Route::get('/syarat-ketentuan', [HomeController::class, 'terms'])->name('terms');
Route::get('/kebijakan-privasi', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/kontak', [HomeController::class, 'contact'])->name('contact');
Route::post('/kontak', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::get('/keranjang', [HomeController::class, 'cart'])->name('cart');
Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');
Route::get('/pembayaran/{pesanan?}', [HomeController::class, 'payment'])->name('payment');

// Auth Routes (Login & Register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Logout Route (Auth Users)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Cart Routes
    Route::post('/cart/sync-login', [HomeController::class, 'syncLoginCart'])->name('cart.sync-login');
    Route::post('/cart/update', [HomeController::class, 'updateCart'])->name('cart.update');

    // User Profile Routes
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password');

    // Order History Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/{pesanan}/upload-bukti', [OrderController::class, 'uploadBukti'])->name('orders.upload-bukti');
    Route::get('/orders/{pesanan}/status', [OrderController::class, 'getStatus'])->name('orders.status');
    Route::get('/orders/{pesanan}', [OrderController::class, 'show'])->name('orders.show');
    Route::delete('/orders/{pesanan}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Review (Ulasan) Routes
    Route::get('/ulasan/{pesanan?}', [UlasanController::class, 'create'])->name('ulasan.create');
    Route::post('/ulasan/{pesanan?}', [UlasanController::class, 'store'])->name('ulasan.store');

    // Karyawan Dashboard Routes
    Route::middleware('role:karyawan,admin')->prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('/dashboard', [KaryawanDashboardController::class, 'index'])->name('dashboard');
        Route::post('/profile/update', [KaryawanDashboardController::class, 'updateProfile'])->name('profile.update');

        // Daftar Pesanan Routes
        Route::get('/pesanan', [KaryawanDashboardController::class, 'pesanan'])->name('pesanan');
        Route::post('/pesanan/{pesanan}/update-status', [KaryawanDashboardController::class, 'updateOrderStatus'])->name('pesanan.update-status');

        // Verifikasi Pembayaran Routes
        Route::get('/verifikasi', [KaryawanDashboardController::class, 'verifikasi'])->name('verifikasi');
        Route::post('/verifikasi/{pembayaran}/setujui', [KaryawanDashboardController::class, 'setujuiPembayaran'])->name('verifikasi.setujui');
        Route::post('/verifikasi/{pembayaran}/tolak', [KaryawanDashboardController::class, 'tolakPembayaran'])->name('verifikasi.tolak');

        // Riwayat Transaksi Routes
        Route::get('/riwayat', [KaryawanDashboardController::class, 'riwayat'])->name('riwayat');
        Route::get('/riwayat/{pesanan}/struk', [KaryawanDashboardController::class, 'struk'])->name('riwayat.struk');

        // Menu Management Routes
        Route::get('/menu', [KaryawanDashboardController::class, 'menu'])->name('menu');
        Route::post('/menu/{menu}/update-stock', [KaryawanDashboardController::class, 'updateMenuStock'])->name('menu.update-stock');

        // Karyawan Financial Reports (Laporan Keuangan) Routes
        Route::get('/laporan-keuangan', [AdminLaporanKeuanganController::class, 'index'])->name('laporan-keuangan.index');
        Route::get('/laporan-keuangan/export/pdf', [AdminLaporanKeuanganController::class, 'exportPdf'])->name('laporan-keuangan.export.pdf');
        Route::get('/laporan-keuangan/export/excel', [AdminLaporanKeuanganController::class, 'exportExcel'])->name('laporan-keuangan.export.excel');
        Route::post('/laporan-keuangan/pengeluaran', [AdminLaporanKeuanganController::class, 'storePengeluaran'])->name('laporan-keuangan.pengeluaran.store');
        Route::put('/laporan-keuangan/pengeluaran/{pengeluaran}', [AdminLaporanKeuanganController::class, 'updatePengeluaran'])->name('laporan-keuangan.pengeluaran.update');
        Route::delete('/laporan-keuangan/pengeluaran/{pengeluaran}', [AdminLaporanKeuanganController::class, 'destroyPengeluaran'])->name('laporan-keuangan.pengeluaran.destroy');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
        Route::delete('/messages/{message}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');
        Route::resource('menu', AdminMenuController::class);
        Route::resource('karyawan', AdminKaryawanController::class);

        // Admin Payment Routes
        Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');
        Route::post('/pembayaran/{pembayaran}/setujui', [AdminPembayaranController::class, 'setujui'])->name('pembayaran.setujui');
        Route::post('/pembayaran/{pembayaran}/tolak', [AdminPembayaranController::class, 'tolak'])->name('pembayaran.tolak');
        Route::post('/pembayaran/settings', [AdminPembayaranController::class, 'updateSettings'])->name('pembayaran.settings');

        // Admin Promo Routes
        Route::resource('promo', AdminPromoController::class);
        Route::post('/promo/{promo}/toggle', [AdminPromoController::class, 'toggleStatus'])->name('promo.toggle');

        // Admin Financial Reports (Laporan Keuangan) Routes
        Route::get('/laporan-keuangan', [AdminLaporanKeuanganController::class, 'index'])->name('laporan-keuangan.index');
        Route::get('/laporan-keuangan/export/pdf', [AdminLaporanKeuanganController::class, 'exportPdf'])->name('laporan-keuangan.export.pdf');
        Route::get('/laporan-keuangan/export/excel', [AdminLaporanKeuanganController::class, 'exportExcel'])->name('laporan-keuangan.export.excel');
        Route::post('/laporan-keuangan/pengeluaran', [AdminLaporanKeuanganController::class, 'storePengeluaran'])->name('laporan-keuangan.pengeluaran.store');
        Route::put('/laporan-keuangan/pengeluaran/{pengeluaran}', [AdminLaporanKeuanganController::class, 'updatePengeluaran'])->name('laporan-keuangan.pengeluaran.update');
        Route::delete('/laporan-keuangan/pengeluaran/{pengeluaran}', [AdminLaporanKeuanganController::class, 'destroyPengeluaran'])->name('laporan-keuangan.pengeluaran.destroy');

        // Admin Review Management (Manajemen Ulasan) Routes
        Route::get('/ulasan', [AdminUlasanController::class, 'index'])->name('ulasan.index');
        Route::post('/ulasan/{ulasan}/reply', [AdminUlasanController::class, 'reply'])->name('ulasan.reply');
        Route::delete('/ulasan/{ulasan}/reply', [AdminUlasanController::class, 'destroyReply'])->name('ulasan.reply.destroy');
        Route::delete('/ulasan/{ulasan}', [AdminUlasanController::class, 'destroy'])->name('ulasan.destroy');
        Route::post('/ulasan/{ulasan}/toggle', [AdminUlasanController::class, 'toggleStatus'])->name('ulasan.toggle');
    });
});
