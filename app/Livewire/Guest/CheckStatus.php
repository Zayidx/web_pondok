<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\JadwalWawancara;

class CheckStatus extends Component
{
    public $nisn = '';
    public $santri = null;
    public $interview = null;
    public $errorMessage = ''; // Pastikan didefinisikan sebagai properti publik

    public function checkStatus()
    {
        $this->validate([
            'nisn' => 'required|digits:10',
        ], [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.digits' => 'NISN harus terdiri dari 10 digit angka.',
        ]);

        $this->santri = PendaftaranSantri::where('nisn', $this->nisn)->first();
        if ($this->santri) {
            $this->interview = JadwalWawancara::where('santri_id', $this->santri->id)->first();
            $this->errorMessage = '';
        } else {
            $this->errorMessage = 'NISN tidak ditemukan.';
            $this->santri = null;
            $this->interview = null;
        }
    }

    public function render()
    {
        return view('livewire.guest.check-status')->layout('components.layouts.register-santri', ['title' => 'Cek Status Pendaftaran']);
    }
}