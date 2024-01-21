<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = ['id_penyewa', 'id_properti'];

    public function penyewa()
    {
        return $this->belongsTo(RegistrasiPenyewa::class, 'id_penyewa');
    }

    public function properti()
    {
        return $this->belongsTo(Properti::class, 'id_properti');
    }
}
