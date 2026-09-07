<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $request->session()->flash('just_logged_in', true);

            $defaultRoute = match (Auth::user()->role) {
                'admin' => route('admin.dashboard'),
                'karyawan' => route('karyawan.dashboard'),
                default => route('home'),
            };

            return redirect()->intended($defaultRoute)->with('success', 'Selamat datang kembali, '.Auth::user()->nama.'!');
        }

        return back()
            ->withErrors(['email' => 'Email atau password tidak valid.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $rules = [
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'no_hp' => ['required', 'string', 'max:15'],
            'nis' => ['required', 'string', 'max:16', 'unique:users,nis'],
            'kelas' => ['required', 'string', 'max:10'],
            'foto_identitas' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ];

        $messages = [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'nis.required' => 'NIS wajib diisi.',
            'nis.max' => 'NIS maksimal 16 karakter.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'kelas.required' => 'Kelas wajib diisi.',
            'foto_identitas.image' => 'File harus berupa gambar.',
            'foto_identitas.max' => 'Ukuran foto maksimal 2MB.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'alamat.required' => 'Alamat wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'terms.accepted' => 'Anda harus menyetujui syarat & ketentuan.',
        ];

        $validated = $request->validate($rules, $messages);

        // Upload foto identitas jika ada
        $fotoPath = null;
        if ($request->hasFile('foto_identitas')) {
            $file = $request->file('foto_identitas');
            $extension = $file->getClientOriginalExtension();
            $filename = 'identitas_'.$validated['nis'].'_'.time().'.'.$extension;
            $fotoPath = $file->storeAs('uploads/identitas', $filename, 'public');
        }

        $user = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'no_hp' => $validated['no_hp'],
            'nis' => $validated['nis'],
            'foto_identitas' => $fotoPath,
            'kelas' => $validated['kelas'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'role' => 'pelanggan',
            'alamat' => $validated['alamat'],
        ]);

        Auth::login($user);
        $request->session()->flash('just_logged_in', true);

        return redirect()->route('home')->with('success', 'Pendaftaran berhasil! Selamat datang, '.$user->nama.'!');
    }
}
