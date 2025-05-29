<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Log;

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
            session(['santri_id' => $santri->id]);
            return redirect()->route('check-status');
        }

        Log::warning('Failed login attempt', ['email' => $this->email]);
        $this->errorMessage = 'Email atau NISN salah.';
    }

    public function render()
    {
        return view('livewire.auth.login-psb')->layout('components.layouts.login-santri-ppdb');
    }
}
