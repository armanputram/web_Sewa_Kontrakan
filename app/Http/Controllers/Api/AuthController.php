<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function registerpengelola(Request $request)
    {
        $validateData = Validator::make($request->all(), [
              'name' => 'required',
            'email' => 'required|email', // Sesuaikan dengan nama tabel yang benar
            'password' => 'required', 
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validateData->errors(),
            ]); // Menggunakan status HTTP untuk validasi gagal
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user =User::create($input);

        $success['token']=$user->createToken('auth_token')->plainTextToken;
        $success['name']=$user->name;
        
  
            return response()->json([
                'status' => true,
                'message' => 'Insert ke database berhasil',
                'data' => $success 
            ]);
    }
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user(); // Menggunakan $user, bukan $auth
    
            $success['token'] = $user->createToken('auth_token')->plainTextToken;
            $success['name'] = $user->name;
    
            return response()->json([
                'status' => true,
                'message' => 'Login berhasil',
                'data' => $success // Kembalikan data sukses
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Login gagal',
                'data' => null
            ]);
        }
    }
}    