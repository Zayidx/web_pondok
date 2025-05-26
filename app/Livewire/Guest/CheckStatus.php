<?php

namespace App\Livewire\Guest;

use Livewire\Component;                  // Mengimpor kelas utama Livewire
use App\Models\PSB\PendaftaranSantri;    // Mengimpor model untuk data pendaftaran santri
use App\Models\PSB\JadwalWawancara;      // Mengimpor model untuk data jadwal wawancara

class CheckStatus extends Component
{
    public $nisn = '';          // Variabel untuk menyimpan NISN yang diinput pengguna
    public $santri = null;      // Variabel untuk menyimpan data santri yang ditemukan
    public $interview = null;   // Variabel untuk menyimpan data jadwal wawancara santri
    public $errorMessage = '';  // Variabel untuk menyimpan pesan error (didefinisikan sebagai properti publik)

    /**
     * Memeriksa status pendaftaran berdasarkan NISN
     * - Melakukan validasi NISN (wajib dan 10 digit)
     * - Mencari data santri dan jadwal wawancara terkait
     * - Menampilkan pesan error jika NISN tidak ditemukan
     */
    public function checkStatus()
    {
        $this->validate([
            'nisn' => 'required|digits:10',  // Validasi: NISN wajib diisi dan harus 10 digit
        ], [
            'nisn.required' => 'NISN wajib diisi.',  // Pesan error jika NISN kosong
            'nisn.digits' => 'NISN harus terdiri dari 10 digit angka.',  // Pesan error jika panjang tidak 10 digit
        ]);

        $this->santri = PendaftaranSantri::where('nisn', $this->nisn)->first();  // Mencari santri berdasarkan NISN
        if ($this->santri) {
            $this->interview = JadwalWawancara::where('santri_id', $this->santri->id)->first();  // Mengambil jadwal wawancara jika ada
            $this->errorMessage = '';  // Mengosongkan pesan error jika santri ditemukan
        } else {
            $this->errorMessage = 'NISN tidak ditemukan.';  // Menampilkan pesan error jika santri tidak ditemukan
            $this->santri = null;  // Mengosongkan data santri
            $this->interview = null;  // Mengosongkan data jadwal wawancara
        }
    }

    /**
     * Menampilkan halaman cek status
     * - Menggunakan view 'livewire.guest.check-status'
     * - Mengatur layout dengan judul 'Cek Status Pendaftaran'
     */
    public function render()
    {
        return view('livewire.guest.check-status')->layout('components.layouts.register-santri', ['title' => 'Cek Status Pendaftaran']);
    }
}