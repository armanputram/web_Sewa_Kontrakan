<?php

namespace App\Http\Controllers;

use App\Models\Properti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class PropertiController extends Controller
{

    public function create(Request $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $request->validate([
                    'nama' => 'required|string',
                    'alamat' => 'required|string',
                    'harga' => 'required|integer',
                    'no_handphone' => 'required|numeric|digits_between:10,12',
                    'deskripsi' => 'required|string',
                    'latitude' => 'required|numeric',  // Validasi latitude
                    'longitude' => 'required|numeric', // Validasi longitude
                    'foto.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:9000',
                ]);

                $user = Auth::guard('registrasi_pemilik')->user();
                $path = public_path('images/');

                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }

                $fotoNames = [];

                if ($request->hasFile('foto')) {
                    foreach ($request->file('foto') as $foto) {
                        $generateNameFile = $this->generateRandomString(10);
                        $imageName = $generateNameFile . '.' . $foto->getClientOriginalExtension();
                        $foto->move($path, $imageName);
                        $fotoNames[] = $imageName;
                    }
                }

                Log::info('Array Nama File: ' . print_r($fotoNames, true));

                $properti = Properti::create([
                    'nama' => $request->input('nama'),
                    'alamat' => $request->input('alamat'),
                    'harga' => $request->input('harga'),
                    'no_handphone' => $request->input('no_handphone'),
                    'deskripsi' => $request->input('deskripsi'),
                    'foto' => count($fotoNames) > 0 ? json_encode(array_map('trim', $fotoNames)) : null,
                    'latitude' => $request->input('latitude'),  // Isi dengan nilai latitude dari request
                    'longitude' => $request->input('longitude'), // Isi dengan nilai longitude dari request
                    'id_pemilik' => $user->id_pemilik,
                    'status' => 'menunggu',
                ]);

                $properti->refresh();

                Log::info('Properti data: ' . print_r($properti->toArray(), true));

                return response()->json(['message' => 'Properti berhasil ditambahkan', 'data' => $properti], 201, [], JSON_UNESCAPED_SLASHES);
            } catch (\Exception $e) {
                Log::error('Error creating property: ' . $e->getMessage());

                DB::rollBack();

                $this->deleteUploadedPhotos($fotoNames);

                return response()->json(['message' => 'Terjadi kesalahan saat menambahkan properti'], 500);
            }
        });
    }


    // Fungsi untuk menghapus foto-foto yang sudah diunggah
    protected function deleteUploadedPhotos($fotoNames)
    {
        $path = public_path('images/');
        foreach ($fotoNames as $fotoName) {
            $filePath = $path . $fotoName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    // Fungsi untuk generate string acak
    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function indexbyid()
    {
        try {
            $pemilik = Auth::guard('registrasi_pemilik')->user();
            $propertis = Properti::where('id_pemilik', $pemilik->id_pemilik)->get();
            if ($propertis->isEmpty()) {
                return response()->json(['message' => 'Tidak ada properti yang ditemukan'], 404);
            }
            return response()->json(['message' => 'Berhasil mendapatkan data properti', 'data' => $propertis], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data properti'], 500);
        }
    }


    public function index()
    {
        try {
            $propertis = Properti::where('status', 'setuju')->inRandomOrder()->get();

            if ($propertis->isEmpty()) {
                return response()->json(['message' => 'Tidak ada properti dengan status "setuju" yang ditemukan'], 404);
            }

            return response()->json(['message' => 'Berhasil mendapatkan data properti dengan status "setuju"', 'data' => $propertis], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data properti'], 500);
        }
    }



    public function updateStatus(Request $request, $id_properti)
    {
        try {
            $request->validate([
                'status' => 'required|in:setuju,tolak',
            ]);

            $properti = Properti::findOrFail($id_properti);

            if ($properti->id_pengelola !== Auth::guard('pengelola')->user()->id) {
                return response()->json(['message' => 'Unauthorized. Anda tidak memiliki izin untuk mengubah status properti ini.'], 403);
            }

            $properti->status = $request->input('status');
            $properti->save();

            return response()->json(['message' => 'Status properti berhasil diperbarui', 'data' => $properti], 200);
        } catch (\Exception $e) {
            Log::error('Error updating property status: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui status properti'], 500);
        }
    }
}
