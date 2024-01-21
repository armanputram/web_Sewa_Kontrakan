<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Kontrak;
use Illuminate\Support\Facades\Auth;

class KontrakController extends Controller
{
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                // Mengambil ID penyewa dari penyewa yang sedang login
                $id_penyewa = Auth::guard('registrasi_penyewa')->user();

                $validator = Validator::make($request->all(), [
                    'id_properti' => 'required|exists:properti,id_properti',
                    'tanggal_mulai' => 'required|date',
                    'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                    'total_biaya' => 'required|integer',
                    'status' => 'required|in:aktif,selesai,batal',
                    'foto.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:9000',
                ]);

                if ($validator->fails()) {
                    return response()->json(['message' => $validator->errors()], 400);
                }

                $fotoNames = [];

                if ($request->hasFile('foto')) {
                    foreach ($request->file('foto') as $foto) {
                        $generateNameFile = $this->generateRandomString(10);
                        $imageName = $generateNameFile . '.' . $foto->getClientOriginalExtension();
                        $foto->move(public_path('images/'), $imageName);
                        $fotoNames[] = $imageName;
                    }
                }

                $kontrak = Kontrak::create([
                    'id_penyewa' => $id_penyewa, // Menggunakan ID penyewa dari otentikasi
                    'id_properti' => $request->input('id_properti'),
                    'tanggal_mulai' => $request->input('tanggal_mulai'),
                    'tanggal_selesai' => $request->input('tanggal_selesai'),
                    'total_biaya' => $request->input('total_biaya'),
                    'status' => $request->input('status'),
                    'foto' => count($fotoNames) > 0 ? json_encode(array_map('trim', $fotoNames)) : null,
                ]);

                return response()->json(['message' => 'Kontrak berhasil disimpan', 'kontrak' => $kontrak], 201);
            } catch (\Exception $e) {
                Log::error('Error creating contract: ' . $e->getMessage());

                // Rollback transaksi jika terjadi kesalahan
                DB::rollBack();

                // Hapus foto-foto yang sudah diunggah sebelumnya
                $this->deleteUploadedPhotos($fotoNames);

                return response()->json(['message' => 'Terjadi kesalahan saat menambahkan kontrak'], 500);
            }
        });
    }
}
