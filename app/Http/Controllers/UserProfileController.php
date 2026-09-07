<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserProfileController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        // Ambil menu favorit dari database sesuai mockup
        $favoritMenu = Menu::whereIn('nama_menu', ['Aren Latte', 'Americano', 'Matcha Latte'])
            ->get();

        return view('profile.index', compact('user', 'favoritMenu'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email,'.$user->id_user.',id_user'],
            'no_hp' => ['required', 'string', 'max:30'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'no_hp.required' => 'Nomor WhatsApp wajib diisi.',
        ]);

        // Simpan perubahan ke database (Update manual karena $timestamps diset false di model User)
        DB::table('users')
            ->where('id_user', $user->id_user)
            ->update([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'no_hp' => $validated['no_hp'],
            ]);

        return redirect()->route('profile')->with('success', 'Profil Anda berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'password_lama' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (! Hash::check($validated['password_lama'], $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak cocok dengan data kami.']);
        }

        DB::table('users')
            ->where('id_user', $user->id_user)
            ->update([
                'password' => Hash::make($validated['password']),
            ]);

        return redirect()->route('profile')->with('success', 'Password Anda berhasil diperbarui.');
    }
}
