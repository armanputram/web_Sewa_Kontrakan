<?php

namespace App\Http\Controllers;


use App\Models\User; // Pastikan ini sesuai dengan lokasi model User Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // Gunakan facade Hash untuk enkripsi password

class AuthController extends Controller
{
    // Method untuk menampilkan form registrasi
    public function register()
    {
        return view('auth.register');
    }

    // Method untuk memproses data registrasi
    public function registerpengelola(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email', // Sesuaikan dengan nama tabel yang benar
            'password' => 'required', 
        ]);

        // Enkripsi password sebelum menyimpan
        $data['password'] = Hash::make($data['password']);
        
        // Buat user baru
        User::create($data);

        // Setelah registrasi, redirect ke halaman login
        // Pertimbangkan untuk menambahkan flash message bahwa registrasi berhasil
        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
    }

    // Method untuk menampilkan form login
    public function login()
    {
        return view('auth.login');
    }
    public function loginPengelola(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }
    
        // Jika login gagal, kirim kembali ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Alamat email atau kata sandi yang Anda masukkan tidak cocok dengan catatan kami.',
        ])->onlyInput('email');
    
    $this->middleware('auth')->except(['login', 'register', 'registerpengelola']);
}

    // Method untuk logout
    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus informasi sesi
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Arahkan kembali ke halaman utama setelah logout
    }   // Tambahkan method untuk proses login dan logout jika diperlukan
}