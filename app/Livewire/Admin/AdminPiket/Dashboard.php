<?php

namespace App\Livewire\Admin\AdminPiket;

use Livewire\Component;
use App\Models\QrSession;
use Illuminate\Support\Str;

class Dashboard extends Component
{
    public $qrCodeUrl;
    public $sessionId;

    // Properti untuk menyimpan daftar santri yang sudah scan
    public $scanLogs = [];

    public function mount()
    {
        // Panggil method untuk membuat QR code baru saat komponen dimuat
        $this->generateNewQrCode();
    }

    public function generateNewQrCode()
    {
        // Hapus sesi QR yang lama untuk memulai sesi yang baru dan bersih
        if ($this->sessionId) {
            QrSession::find($this->sessionId)->delete();
        }

        // Buat token acak yang unik
        $token = Str::random(40);
        
        // Buat record baru di tabel qr_sessions
        $session = QrSession::create([
            'token' => $token,
            'expires_at' => now()->addMinutes(5), // QR Code berlaku selama 5 menit
        ]);

        // Simpan ID sesi dan buat URL menggunakan helper url() standar
        $this->sessionId = $session->id;
        // Gunakan env NGROK_URL jika ada, jika tidak gunakan url() biasa
        // Ini penting agar QR Code bisa di-scan dari HP saat development
        $baseUrl = env('NGROK_URL', url('/'));
        $this->qrCodeUrl = rtrim($baseUrl, '/') . '/santri/absensi/' . $token;
        
        // Kosongkan log scan setiap kali QR baru dibuat
        $this->scanLogs = [];
    }

    // Method untuk memeriksa status dan mengambil log scan
    public function checkScanStatus()
    {
        if (!$this->sessionId) {
            return;
        }

        // Ambil data sesi QR yang aktif beserta relasi ke scanLogs
        $session = QrSession::with('scanLogs.santri')->find($this->sessionId);

        // Jika sesi ditemukan, perbarui properti scanLogs
        if ($session) {
            $this->scanLogs = $session->scanLogs;
        }
    }

    public function render()
    {
        // Tampilkan view
        return view('livewire.admin.admin-piket.dashboard');
    }
}
