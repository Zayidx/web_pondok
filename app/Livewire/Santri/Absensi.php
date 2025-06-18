<?php

namespace App\Livewire\Santri;

use App\Models\AbsensiDetail;
use App\Models\QrSession;
use App\Models\Santri;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Absensi extends Component
{
    public $message;
    public $status;
    public $token;
    public ?Santri $santri;
    public $scanCompleted = false;
    public $isValidSession = false;

    public function mount($token)
    {
        $this->token = $token;
        $this->santri = Auth::guard('santri')->user();
        
        // Komentar: Cari sesi QR berdasarkan token.
        $session = QrSession::where('token', $this->token)
            ->with('absensi') // Eager load relasi absensi
            ->first();

        // Validasi #1: Sesi tidak ditemukan.
        if (!$session) {
            $this->status = 'error';
            $this->message = 'QR Code tidak valid atau sesi tidak ditemukan.';
            return;
        }

        // Validasi #2: Sesi sudah kedaluwarsa.
        if (now()->gt($session->expires_at)) {
            $this->status = 'error';
            $this->message = 'Maaf, QR Code ini sudah kedaluwarsa.';
            return;
        }
        
        // Validasi #3: Santri tidak terdaftar di kelas yang benar.
        if ($this->santri->kelas_id !== $session->absensi->kelas_id) {
            $this->status = 'error';
            $this->message = 'Anda tidak terdaftar pada kelas untuk sesi absensi ini.';
            return;
        }
        
        // Komentar: Cari detail absensi untuk santri dan sesi ini.
        $absensiDetail = AbsensiDetail::where('absensi_id', $session->absensi_id)
                                    ->where('santri_id', $this->santri->id)
                                    ->first();

        // Validasi #4: Santri sudah tercatat hadir.
        if ($absensiDetail && $absensiDetail->status === 'Hadir') {
            $this->status = 'error';
            $this->message = 'Anda sudah melakukan absensi untuk sesi ini.';
            $this->scanCompleted = true; // Langsung tampilkan pesan error
            return;
        }
        
        // Jika semua validasi lolos
        $this->isValidSession = true;
    }

    /**
     * Komentar: Method ini akan dipanggil saat santri menekan tombol konfirmasi.
     */
    public function confirmScan()
    {
        if (!$this->isValidSession) {
            return;
        }
        
        $session = QrSession::where('token', $this->token)->first();
        
        if ($session) {
            // Komentar: Update record di tabel absensi_details.
            AbsensiDetail::where('absensi_id', $session->absensi_id)
                ->where('santri_id', $this->santri->id)
                ->update([
                    'status' => 'Hadir',
                    'jam_hadir' => now()
                ]);

            $this->status = 'success';
            $this->message = 'Absensi Anda berhasil tercatat. Terima kasih!';
        } else {
            $this->status = 'error';
            $this->message = 'Gagal! Sesi QR tidak ditemukan lagi.';
        }
        
        $this->scanCompleted = true; // Tampilkan halaman hasil
    }

    public function render()
    {
        return view('livewire.santri.absensi');
    }
}
