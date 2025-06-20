<?php

namespace App\Livewire\Absensi;

use App\Models\Absensi\Absensi;
use App\Models\Absensi\AbsensiDetail;
use App\Models\Absensi\QrSession; // QrSession untuk Santri
use App\Models\Absensi\ScanLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.app-mobile')] // Menggunakan layout mobile untuk santri
class Scan extends Component
{
    public $token;
    public $message;
    public $status; // Untuk menampilkan status di halaman ini

    public function mount($token)
    {
        $this->token = $token;
        $this->processSantriScan(); // Hanya proses scan santri
    }

    protected function processSantriScan()
    {
        Log::info('[ScanSantri] Memproses scan santri. Token: ' . $this->token);
        $qrSession = QrSession::where('token', $this->token)->first();
        $santriId = Auth::guard('santri')->id();

        // Pengecekan 1: Apakah sesi QR valid?
        if (!$qrSession) {
            $this->status = 'error';
            $this->message = 'QR Code tidak valid atau tidak ditemukan.';
            return;
        }

        // Pengecekan 2: Apakah sesi QR sudah kedaluwarsa?
        if (now()->greaterThan($qrSession->expires_at)) {
            $this->status = 'error';
            $this->message = 'Sesi QR Code sudah kedaluwarsa.';
            return;
        }

        // Pastikan yang scan adalah Santri (menggunakan guard 'santri')
        if (!Auth::guard('santri')->check()) {
            $this->status = 'error';
            $this->message = 'Absensi ini hanya untuk santri.';
            return;
        }

        $absensi = Absensi::find($qrSession->absensi_id);
        // Pengecekan jika absensi atau jadwal pelajaran tidak ditemukan
        if (!$absensi || !$absensi->jadwalPelajaran) {
            $this->status = 'error';
            $this->message = 'Data absensi atau jadwal pelajaran tidak ditemukan.';
            return;
        }
        $jadwal = $absensi->jadwalPelajaran;

        // Pengecekan 3: Apakah santri yang scan terdaftar di kelas yang benar?
        if (auth('santri')->user()->kelas_id != $jadwal->kelas_id) {
            $this->status = 'error';
            $this->message = 'Anda tidak terdaftar di kelas ini.';
            return;
        }
        
        // Pengecekan 4: Apakah santri sudah pernah scan untuk sesi QR yang sama?
        $pernahScan = ScanLog::where('qr_session_id', $qrSession->id)
            ->where('santri_id', $santriId)->exists();
            
        if($pernahScan) {
            $this->status = 'error';
            $this->message = 'Anda sudah melakukan scan dengan QR Code ini.';
            return;
        }
        
        // Logika Baru: Mencatat setiap percobaan scan ke tabel 'scan_logs'
        // Ini digunakan untuk menampilkan daftar scan secara real-time di halaman admin
        ScanLog::create([
            'qr_session_id' => $qrSession->id,
            'santri_id' => $santriId
        ]);

        // Logika Utama: Memperbarui atau membuat catatan di 'absensi_details'
        AbsensiDetail::updateOrCreate(
            ['absensi_id' => $absensi->id, 'santri_id' => $santriId],
            ['status' => 'Hadir', 'jam_hadir' => now()]
        );
        
        $this->status = 'success';
        $this->message = 'Absensi berhasil! Anda tercatat hadir untuk mata pelajaran ' . $jadwal->mata_pelajaran . '.';
        $this->dispatch('scanUpdated'); // Memberi tahu komponen admin santri untuk refresh
    }

    public function render()
    {
        return view('livewire.absensi.scan');
    }
}