<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontrak extends Model
{
    use HasFactory;

    protected $table = 'kontrak'; // Sesuaikan dengan nama tabel di database

    protected $primaryKey = 'id_kontrak'; // Sesuaikan dengan nama primary key di tabel

    protected $fillable = [
        'id_penyewa',
        'id_properti',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_biaya',
        'status',
        'foto',
    ];

    protected $casts = [
        'foto' => 'json', // Cast kolom 'foto' ke tipe JSON
    ];

    public function properti()
    {
        return $this->belongsTo(Properti::class, 'id_properti', 'id_properti');
    }

    public function penyewa()
    {
        return $this->belongsTo(RegistrasiPenyewa::class, 'id_penyewa', 'id_penyewa');
    }
}
