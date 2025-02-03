<?php

namespace App\Livewire\Auth;

use App\Models\Santri;
use App\Models\User;
use Detection\MobileDetect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

            if (Auth::guard('santri')->attempt($data)) {
                return to_route('santri.dashboard');
            }

            return back()->withErrors(['credentials' => 'NISN atau password salah']);
        } catch (\Throwable $th) {
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
