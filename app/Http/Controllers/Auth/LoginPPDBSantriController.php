<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Auth;

class LoginPPDBSantriController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login-ppdb-santri');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nomor_pendaftaran' => 'required',
            'password' => 'required'
        ]);

        $santri = PendaftaranSantri::where('nomor_pendaftaran', $credentials['nomor_pendaftaran'])->first();

        if ($santri && $credentials['password'] === $santri->password) {
            Auth::guard('santri')->login($santri);
            session(['santri_id' => $santri->id]);
            session(['santri_nama' => $santri->nama_lengkap]);
            
            // Redirect to check-status first
            return redirect()->route('santri.check-status');
        }

        return back()->withErrors([
            'nomor_pendaftaran' => 'Nomor pendaftaran atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('santri')->logout();
        $request->session()->forget(['santri_id', 'santri_nama']);
        return redirect()->route('login-ppdb-santri');
    }
} 