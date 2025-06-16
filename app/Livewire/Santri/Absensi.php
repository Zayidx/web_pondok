<?php

namespace App\Livewire\Santri;

use App\Models\ScanLog; // <-- Import model baru
use Livewire\Component;
use App\Models\QrSession;
use App\Models\Santri;
use Illuminate\Support\Facades\Auth;

class Absensi extends Component
{
    // ... (properti lain tetap sama) ...
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
        $session = QrSession::where('token', $this->token)->first();

        if (!$session) {
            $this->status = 'error';
            $this->message = 'QR Code tidak valid atau tidak ditemukan.';
        } elseif (now()->gt($session->expires_at)) {
            $this->status = 'error';
            $this->message = 'Maaf, QR Code ini sudah kedaluwarsa.';
        } else {
            // Cek apakah santri ini sudah pernah scan untuk sesi ini
            $alreadyScanned = ScanLog::where('qr_session_id', $session->id)
                                     ->where('santri_id', $this->santri->id)
                                     ->exists();
            if ($alreadyScanned) {
                 $this->status = 'error';
                 $this->message = 'Anda sudah melakukan absensi untuk sesi ini.';
                 $this->scanCompleted = true;
            } else {
                $this->isValidSession = true;
            }
        }
    }


    // Method ini akan dipanggil saat santri menekan tombol konfirmasi
    public function confirmScan()
    {
        if (!$this->isValidSession) {
            return;
        }

        $session = QrSession::where('token', $this->token)->first();

        if ($session) {
            // PERUBAHAN UTAMA: Buat record baru di tabel scan_logs
            ScanLog::create([
                'qr_session_id' => $session->id,
                'santri_id' => $this->santri->id,
            ]);

            $this->status = 'success';
            $this->message = 'Absensi Anda berhasil tercatat. Terima kasih!';
            $this->scanCompleted = true;
        } else {
            $this->status = 'error';
            $this->message = 'Gagal! Sesi QR tidak ditemukan lagi.';
            $this->scanCompleted = true;
        }
    }

    public function render()
    {
        return view('livewire.santri.absensi');
    }
}

