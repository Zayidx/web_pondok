<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class LoginPsb extends Component
{
    public $email = '';
    public $password = '';
    public $errorMessage = '';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|digits:10',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.digits' => 'Password harus 10 digit (NISN).',
        ]);

        $santri = PendaftaranSantri::where('email', $this->email)->first();

        if ($santri && $this->password === $santri->nisn) {
            Log::info('Santri logged in successfully', ['email' => $this->email, 'santri_id' => $santri->id]);
            
            // Store santri data in session
            session(['santri_id' => $santri->id]);
            Auth::guard('santri')->login($santri);

            // Redirect based on santri status
            if ($santri->status_santri === 'sedang_ujian') {
                return redirect()->route('santri.dashboard-ujian');
            } else if ($santri->status_santri === 'diterima') {
                return redirect()->route('e-ppdb.check-status');
            } else {
                return redirect()->route('santri.dashboard');
            }
        }

        Log::warning('Failed login attempt', ['email' => $this->email]);
        $this->errorMessage = 'Email atau NISN salah.';
    }

    #[Layout('components.layouts.login-santri-ppdb')]
    public function render()
    {
        return view('livewire.auth.login-psb');
    }
}
