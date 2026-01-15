<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    // 1. Arahkan user ke halaman Login Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Google mengembalikan user ke website kita (Callback)
    public function handleGoogleCallback()
    {
        try {
            // FIX: Tambahkan stateless() agar tidak error 'InvalidState' di localhost
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Cari user berdasarkan google_id atau email
            $finduser = User::where('google_id', $googleUser->id)
                            ->orWhere('email', $googleUser->email)
                            ->first();

            if($finduser){
                // Jika user sudah ada, langsung login
                
                // Update google_id jika belum ada (misal dulunya daftar manual)
                if(empty($finduser->google_id)){
                    $finduser->update(['google_id' => $googleUser->id]);
                }
                
                Auth::login($finduser);
                return redirect()->intended('/');

            } else {
                // Jika user belum ada, buat user baru (Register Otomatis)
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id'=> $googleUser->id,
                    'password' => bcrypt('123456dummy'), // Password dummy acak
                    'role' => 'customer', // Default jadi customer
                ]);

                Auth::login($newUser);
                return redirect()->intended('/');
            }

        } catch (Exception $e) {
            // DEBUG: Tampilkan pesan error asli di layar jika gagal
            dd($e->getMessage());
        }
    }
}