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
    // 2. Google mengembalikan user ke website kita (Callback)
    public function handleGoogleCallback()
    {
        try {
            // Ambil data dari Google
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Cari user di database
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                // KONDISI 1: User Lama
                // Update google_id jika belum ada
                if (empty($user->google_id)) {
                    $user->update(['google_id' => $googleUser->id]);
                }
            } else {
                // KONDISI 2: User Baru (Register Otomatis)
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt('123456dummy'),
                    'role' => 'customer', // Default user baru pasti customer
                ]);
            }

            // --- BAGIAN INI YANG DIUBAH ---

            // 1. Login-kan user yang sudah didapat/dibuat di atas
            Auth::login($user);

            // 2. Cek Role untuk menentukan tujuan
            if ($user->role === 'admin') {
                return redirect('/admin'); // Admin langsung ke Dashboard
            }

            // 3. Selain admin, lempar ke Home
            return redirect('/');

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}