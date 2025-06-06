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
use App\Models\PSB\PendaftaranSantri;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.auth')]
#[Title('Login Santri')]
class LoginSantri extends Component
{
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
        $credentials = [
            'nisn' => $this->nisn,
            'password' => $this->password,
        ];

        if (Auth::guard('santri')->attempt($credentials)) {
            session()->regenerate();
            return redirect()->intended(route('santri.dashboard'));
        }

        $this->addError('credentials', 'Login gagal, password atau NISN salah.');
    }

    public function logout()
    {
        Auth::guard('santri')->logout();
        return redirect('/auth/login-santri');
    }

    public function render()
    {
        if ($this->is_mobile) return view('livewire.mobile.auth.login-mobile-santri')->layout('components.layouts.auth-mobile');
        return view('livewire.auth.login-santri');
    }
}
