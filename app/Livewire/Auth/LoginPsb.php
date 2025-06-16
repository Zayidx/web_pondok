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

    protected $rules = [
        'email' => 'required|email',
        'nisn' => 'required|numeric|digits:10'
    ];

    protected $messages = [
        'email.required' => 'Email harus diisi',
        'email.email' => 'Format email tidak valid',
        'nisn.required' => 'NISN harus diisi',
        'nisn.numeric' => 'NISN harus berupa angka',
        'nisn.digits' => 'NISN harus 10 digit'
    ];

    public function resetError($field)
    {
        $this->resetValidation($field);
        $this->errorMessage = null;
    }

    public function login()
    {
        try {
            $this->validate();

            $santri = PendaftaranSantri::where('email', $this->email)
                ->where('nisn', $this->nisn)
                ->first();

            if ($santri) {
                Auth::guard('pendaftaran_santri')->login($santri);
                session(['santri_id' => $santri->id]);
                session(['login_time' => now()]);

                Log::info('Successful login', [
                    'santri_id' => $santri->id,
                    'email' => $santri->email
                ]);

                if ($santri->status_santri === 'sedang_ujian') {
                    $ujian = $santri->ujian;
                    if ($ujian) {
                        return redirect()->route('check-status');
                    }
                }

                return redirect()->route('check-status');
            }

            $this->errorMessage = 'Email atau NISN tidak sesuai. Silakan coba lagi.';
            Log::warning('Failed login attempt', ['email' => $this->email]);
        } catch (\Exception $e) {
            Log::error('Login error', ['error' => $e->getMessage()]);
            $this->errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
        }
    }

    #[Layout('components.layouts.login-santri-ppdb')]
    public function render()
    {
        // Check if session is expired (3 hours)
        if (session()->has('login_time')) {
            $loginTime = session('login_time');
            if (now()->diffInHours($loginTime) >= 3) {
                Auth::guard('pendaftaran_santri')->logout();
                session()->forget(['santri_id', 'login_time']);
            }
        }

        return view('livewire.auth.login-psb');
    }
}