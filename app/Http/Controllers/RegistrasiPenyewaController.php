<?php

namespace App\Http\Controllers;

use App\Models\RegistrasiPenyewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class RegistrasiPenyewaController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|unique:registrasi_penyewa,email',
                'noHP' => 'required|numeric|digits_between:10,12',
                'alamat' => 'required|string',
                'password' => 'required|string|min:6',
            ]);

            $penyewa = RegistrasiPenyewa::create([
                'email' => $request->input('email'),
                'noHP' => $request->input('noHP'),
                'alamat' => $request->input('alamat'),
                'password' => bcrypt($request->input('password')),
            ]);

            return response()->json(['message' => 'Registrasi berhasil'], 200);
        } catch (\Exception $e) {
            Log::error('Error during registration: ' . $e->getMessage());
            return response()->json(['message' => 'Registrasi gagal', 'error' => 'Email sudah terdaftar. Gunakan email lain.'], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $user = RegistrasiPenyewa::where('email', $request->email)->first();

            if ($user && $user->attemptLogin($request->password)) {
                $token = $user->createToken('token-name')->plainTextToken;
                return response()->json(['token' => $token, 'role' => $user->role, 'id_penyewa' => $user->id_penyewa], 200);
            }

            throw ValidationException::withMessages([
                'email' => ['Email atau kata sandi yang Anda masukkan tidak sesuai.'],
            ]);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat mencoba login.'], 500);
        }
    }



    public function logout(Request $request)
    {
        try {
            $user = RegistrasiPenyewa::user();
            if ($user) {
                $user->token()->delete();
                return response()->json(['message' => 'Logout berhasil'], 200);
            }
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return response()->json(['message' => 'Logout gagal. Kesalahan server.'], 500);
        }
    }


    public function index()
    {
        $penyewa = RegistrasiPenyewa::all();
        return response()->json($penyewa);
    }

    public function editProfile(Request $request, $id_penyewa)
    {
        $penyewa = RegistrasiPenyewa::find($id_penyewa);

        if (!$penyewa) {
            return response()->json(['message' => 'Penyewa tidak ditemukan'], 404);
        }

        $rules = [
            'email' => 'email|unique:registrasi_penyewa,email,' . $id_penyewa . ',id_penyewa',
            'noHP' => 'numeric|digits_between:10,12',
            'alamat' => 'string',
            'password' => 'string|min:6',
        ];

        $request->validate($rules);

        $dataToUpdate = [];

        if ($request->filled('email')) {
            $dataToUpdate['email'] = $request->email;
        }

        if ($request->filled('noHP')) {
            $dataToUpdate['noHP'] = $request->noHP;
        }

        if ($request->filled('alamat')) {
            $dataToUpdate['alamat'] = $request->alamat;
        }

        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $penyewa->update($dataToUpdate);

        return response()->json(['message' => 'Profil berhasil diperbarui', 'penyewa' => $penyewa]);
    }
    public function destroy($id_penyewa)
    {
        try {
            $penyewa = RegistrasiPenyewa::find($id_penyewa);

            if (!$penyewa) {
                return response()->json(['message' => 'Penyewa tidak ditemukan'], 404);
            }

            $penyewa->delete();

            return response()->json(['message' => 'Akun berhasil dihapus'], 200);
        } catch (\Exception $e) {
            Log::error('Error during account deletion: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus akun'], 500);
        }
    }
}
