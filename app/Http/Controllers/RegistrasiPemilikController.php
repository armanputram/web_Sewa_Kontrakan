<?php

namespace App\Http\Controllers;

use App\Models\RegistrasiPemilik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\Properti;


class RegistrasiPemilikController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|unique:registrasi_pemilik,email',
                'noHP' => 'required|numeric|digits_between:10,12',
                'alamat' => 'required|string',
                'password' => 'required|string|min:6',
            ]);

            $pemilik = RegistrasiPemilik::create([
                'email' => $request->email,
                'noHP' => $request->noHP,
                'alamat' => $request->alamat,
                'password' => bcrypt($request->password),
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

            $user = RegistrasiPemilik::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                $token = $user->createToken('auth-token')->plainTextToken;

                // Simpan token ke dalam kolom api_token
                $user->forceFill(['api_token' => $token])->save();

                Log::info('User logged in: ' . $user->email);

                return response()->json(['token' => $token, 'role' => $user->role, 'id_pemilik' => $user->id_pemilik], 200);
            }

            throw ValidationException::withMessages([
                'email' => ['Email atau kata sandi yang Anda masukkan tidak sesuai.'],
            ]);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());

            return response()->json(['message' => 'Terjadi kesalahan saat mencoba login.'], 500);
        }
    }



    public function index()
    {
        $pemilik = RegistrasiPemilik::all();
        return response()->json($pemilik);
    }

    public function editProfile(Request $request, $id_pemilik)
    {
        $pemilik = RegistrasiPemilik::find($id_pemilik);

        if (!$pemilik) {
            return response()->json(['message' => 'Pemilik tidak ditemukan'], 404);
        }

        $rules = [
            'email' => 'email|unique:registrasi_pemilik,email,' . $id_pemilik . ',id_pemilik',
            'noHP' => 'numeric|digits_between:10,12',
            'alamat' => 'string',
            'password' => 'string|min:6',
        ];

        // Hanya validasi jika data diisi
        $request->validate($rules);

        $dataToUpdate = [];

        // Tambahkan data ke $dataToUpdate hanya jika ada data yang diisi
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

        $pemilik->update($dataToUpdate);

        return response()->json(['message' => 'Profil berhasil diperbarui', 'pemilik' => $pemilik]);
    }

    public function destroy($id_pemilik)
    {
        try {
            $pemilik = RegistrasiPemilik::find($id_pemilik);

            if (!$pemilik) {
                return response()->json(['message' => 'Pemilik tidak ditemukan'], 404);
            }

            // Delete associated properties
            $propertis = Properti::where('id_pemilik', $pemilik->id_pemilik)->get();

            foreach ($propertis as $properti) {
                try {
                    $this->deleteUploadedPhotos(json_decode($properti->foto, true));
                    $properti->delete();
                    Log::info('Properti berhasil dihapus: ' . $properti->id_properti);
                } catch (\Exception $propertiException) {
                    Log::error('Error deleting property (id_properti=' . $properti->id_properti . '): ' . $propertiException->getMessage());
                }
            }

            // Delete the user account
            try {
                $pemilik->delete();
                Log::info('Akun pemilik berhasil dihapus: ' . $pemilik->id_pemilik);
                return response()->json(['message' => 'Akun pemilik berhasil dihapus'], 200);
            } catch (\Exception $userException) {
                Log::error('Error deleting user account (id_pemilik=' . $pemilik->id_pemilik . '): ' . $userException->getMessage());
                return response()->json(['message' => 'Terjadi kesalahan saat menghapus akun pemilik'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error in destroy method: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus akun pemilik'], 500);
        }
    }

    // ...

    protected function deleteUploadedPhotos($fotoNames)
    {
        $path = public_path('images/');
        foreach ($fotoNames as $fotoName) {
            try {
                $filePath = $path . $fotoName;
                if (file_exists($filePath)) {
                    unlink($filePath);
                    Log::info('Foto berhasil dihapus: ' . $filePath);
                }
            } catch (\Exception $fileException) {
                Log::error('Error deleting photo (' . $fotoName . '): ' . $fileException->getMessage());
            }
        }
    }
}
