<?php

namespace App\Livewire\Auth;

use App\Models\Santri;
use App\Models\User;
use Detection\MobileDetect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

class LoginSantri extends Component
{
    #[Title('Halaman Login Santri')]

    #[Validate('required')]
    public $nisn;

    #[Validate('required')]
    public $password;

    public $is_mobile;

    public function mount()
    {
        $mobile = new MobileDetect();
        $this->is_mobile = $mobile->isMobile();
    }

    public function login()
    {
        try {
            $this->validate();

            $data = [
                'nisn' => $this->nisn,
                'password' => $this->password,
            ];

            Log::info('Attempting santri login', ['nisn' => $this->nisn]);

            if (Auth::guard('santri')->attempt($data)) {
                Log::info('Santri login successful', ['nisn' => $this->nisn]);
                return to_route('santri.dashboard');
            }

            Log::warning('Santri login failed', ['nisn' => $this->nisn]);
            return back()->withErrors(['credentials' => 'NISN atau password salah']);
        } catch (\Throwable $th) {
            Log::error('Santri login error', [
                'nisn' => $this->nisn,
                'error' => $th->getMessage()
            ]);
            return back()->withErrors(['credentials' => $th->getMessage()]);
        }
    }

    public function logout()
    {
        Auth::guard('santri')->logout();
        return redirect('/auth/login-santri');
    }

    public function render()
    {
        if ($this->is_mobile) return view('livewire.mobile.auth.login-mobile-santri')->layout('components.layouts.auth-mobile');
        return view('livewire.auth.login-santri')->layout('components.layouts.auth');
    }
}
