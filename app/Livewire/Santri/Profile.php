<?php

namespace App\Livewire\Santri;

use App\Livewire\Forms\ProfileSantriForm;
use App\Models\Santri;
use App\Models\User;
use Detection\MobileDetect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;

class Profile extends Component
{
    #[Title('Profile Santri')]
    public $profile;
    public $showPassword, $userId, $isMobile;
    public $nama, $password;

    public function mount()
    {
        $this->profile = Santri::with('kamar', 'kelas', 'semester', 'angkatan')->where('nama', Auth::guard('santri')->user()->nama)->first();
        $mobile = new MobileDetect();
        $this->isMobile = $mobile->isMobile();
    }

    public function edit($id)
    {
        $this->userId = $id;
        $userEdit = Santri::findOrFail($id);
        $this->nama = $userEdit->nama;
    }

    public function close()
    {
        $this->userForm->reset();
    }

    public function updateProfileSantri()
    {
        $this->validate([
            'nama' => 'required|string',
            'password' => 'nullable|min:8'
        ]);

        try {
            if ($this->password) {
                Santri::findOrFail($this->userId)->update([
                    'password' => Hash::make($this->password),
                ]);
            }

            Santri::findOrFail($this->userId)->update([
                'nama' => $this->nama,
            ]);

            return to_route('santri.profile');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function render()
    {
        if ($this->isMobile) return view('livewire.mobile.santri.profile')->layout('components.layouts.app-mobile');
        return view('livewire.santri.profile');
    }
}
