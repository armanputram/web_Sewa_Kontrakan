<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Properti extends Model
{
    use HasFactory;

    protected $table = 'properti';

    protected $primaryKey = 'id_properti';
    protected $fillable = [
        'nama',
        'alamat',
        'harga',
        'no_handphone',
        'deskripsi',
        'foto',
        'id_pemilik',
        'status',
        'latitude',
        'longitude',
    ];

    // Jika kolom foto diatur sebagai tipe data JSON, Anda dapat mengonversinya ke array saat mengambil data dari database.
    protected $casts = [
        'foto' => 'json',
    ];


    public function pemilik()
    {
        return $this->belongsTo(RegistrasiPemilik::class, 'id_pemilik');
    }
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'id_properti');
    }
}
