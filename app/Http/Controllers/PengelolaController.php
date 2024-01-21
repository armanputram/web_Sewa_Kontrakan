<?php

namespace App\Http\Controllers;
use App\Models\Properti; // Pastikan untuk menggunakan model Properti yang sesuai
use Illuminate\Http\Request;

class PengelolaController extends Controller
{
    public function __construct()
    {
        // Menambahkan middleware 'auth' pada metode tertentu
        $this->middleware('auth')->only(['dashboard', 'updateStatus', 'riwayat']);
    }
    
    public function index()
    {
        // Logika atau operasi lain yang sesuai dengan halaman utama pengelola
        return view('index');
    }

    public function dashboard()
    {
        // if (auth()->check()) {
            $propertis = Properti::all(); // Ambil semua data properti
            return view('dashboard', compact('propertis')); // Kirim data ke view
        // } else {
        //     return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
    
        public function updateStatus(Request $request, $id)
        {
            $request->validate([
                'status' => 'required|in:setuju,tolak',
            ]);
        
            $properti = Properti::find($id);
        
            if (!$properti) {
                return response()->json(['error' => 'Properti tidak ditemukan.'], 404);
            }
        
            // Simpan status sebelum diubah
            $oldStatus = $properti->status;
        
            // Update status properti
            $properti->status = $request->input('status');
            $properti->save();
        
            // Cek apakah status berubah menjadi setuju atau tolak
            if ($oldStatus == 'menunggu' && ($properti->status == 'setuju' || $properti->status == 'tolak')) {
                // Pindahkan properti ke riwayat
                RiwayatProperti::create([
                    'nama' => $properti->nama,
                    'alamat' => $properti->alamat,
                    'harga' => $properti->harga,
                    'status' => $properti->status,
                    // tambahkan kolom lain sesuai kebutuhan
                ]);
            }
        
            return response()->json(['data' => ['status' => $properti->status]]);
        }
        

    public function riwayat()
   {
    $riwayatPropertis = Properti::where('status', '<>', 'menunggu')->get();
    return view('riwayat', compact('riwayatPropertis'));
    }

}