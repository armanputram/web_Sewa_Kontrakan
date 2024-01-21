<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class Pengelola extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'pengelola';
    protected $primaryKey = 'id_pengelola';
    protected $fillable = [
        'name', 'email', 'password',
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
}
