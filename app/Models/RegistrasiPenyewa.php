<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class RegistrasiPenyewa extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'registrasi_penyewa';
    protected $primaryKey = 'id_penyewa';
    protected $fillable = [
        'email', 'noHP', 'alamat', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function attemptLogin($password)
    {
        return Hash::check($password, $this->password);
    }

    public function findForPassport($email)
    {
        return $this->where('email', $email)->first();
    }
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'id_penyewa');
    }
}
