<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;

class LoginPsb extends Component
{
    public $email;
    public $nisn;
    public $errorMessage;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'nisn' => 'required'
        ]);

        $santri = PendaftaranSantri::where('email', $this->email)
            ->where('nisn', $this->nisn)
            ->first();

        if ($santri) {
            Auth::guard('santri')->login($santri);
            session(['santri_id' => $santri->id]);
            session(['login_time' => now()]);

            Log::info('Successful login', [
                'santri_id' => $santri->id,
                'email' => $santri->email
            ]);

            if ($santri->status_santri === 'sedang_ujian') {
                $ujian = $santri->ujian;
                if ($ujian) {
                    return redirect()->route('santri.mulai-ujian', ['ujianId' => $ujian->id]);
                }
            }

            return redirect()->route('santri.dashboard');
        }

        Log::warning('Failed login attempt', ['email' => $this->email]);
        $this->errorMessage = 'Email atau NISN salah.';
    }

    #[Layout('components.layouts.login-santri-ppdb')]
    public function render()
    {
        // Check if session is expired (3 hours)
        if (session()->has('login_time')) {
            $loginTime = session('login_time');
            if (now()->diffInHours($loginTime) >= 3) {
                Auth::guard('santri')->logout();
                session()->forget(['santri_id', 'login_time']);
            }
        }

        return view('livewire.auth.login-psb');
    }
}
