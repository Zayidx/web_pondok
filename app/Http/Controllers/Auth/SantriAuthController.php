<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Hash;

class SantriAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:santri')->except('logout');
    }

    public function showLoginForm()
    {
        if (Auth::guard('santri')->check()) {
            return redirect()->route('e-ppdb.ujian.dashboard');
        }
        return view('auth.login-santri');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nisn' => 'required',
            'password' => 'required',
        ]);

        $santri = PendaftaranSantri::where('nisn', $credentials['nisn'])->first();

        if (!$santri) {
            return back()->with('error', 'NISN tidak ditemukan.');
        }

        if (!Hash::check($credentials['password'], $santri->password)) {
            return back()->with('error', 'Password salah.');
        }

        if ($santri->status_santri !== 'sedang_ujian') {
            return back()->with('error', 'Anda tidak memiliki akses ke halaman ujian.');
        }

        Auth::guard('santri')->login($santri);
        
        return redirect()->route('e-ppdb.ujian.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('santri')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login-ppdb-santri');
    }
} 