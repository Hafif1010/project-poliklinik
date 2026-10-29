<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil / ganti password.
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Update password pengguna yang sedang login.
     */
    public function updatePassword(ChangePasswordRequest $request)
    {
        // User model sudah memiliki cast 'hashed' pada kolom password,
        // sehingga password otomatis di-hash saat disimpan tanpa perlu Hash::make()
        $user = Auth::user();

        $user->update([
            'password' => $request->password,
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}
