<?php

namespace App\Livewire\Absensi;

use App\Models\Absensi\Absensi;
use App\Models\Absensi\AbsensiDetail;
use App\Models\Absensi\QrSession;
use App\Models\Absensi\ScanLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.app-mobile')]
class Scan extends Component
{
    public $token;
    public $message;
    public $status;

    public function mount($token)
    {
        $this->token = $token;
        $this->processSantriScan();
    }

    protected function processSantriScan()
    {
        Log::info('[ScanSantri] Memproses scan santri. Token: ' . $this->token);
        $qrSession = QrSession::where('token', $this->token)->first();
        $santriId = Auth::guard('santri')->id();
        $santri = Auth::guard('santri')->user();

        if (!$qrSession) {
            $this->status = 'error';
            $this->message = 'QR Code tidak valid atau tidak ditemukan.';
            return;
        }

        if (now()->greaterThan($qrSession->expires_at)) {
            $this->status = 'error';
            $this->message = 'Sesi QR Code sudah kedaluwarsa.';
            return;
        }

        if (!$santri) {
            $this->status = 'error';
            $this->message = 'Absensi ini hanya untuk santri yang login.';
            return;
        }

        $absensi = Absensi::find($qrSession->absensi_id);
        if (!$absensi || !$absensi->jadwalPelajaran) {
            $this->status = 'error';
            $this->message = 'Data absensi atau jadwal pelajaran tidak ditemukan.';
            return;
        }
        $jadwal = $absensi->jadwalPelajaran;

        if ($santri->kelas_id != $jadwal->kelas_id) {
            $this->status = 'error';
            $this->message = 'Anda tidak terdaftar di kelas ini.';
            return;
        }
        
        $existingDetail = AbsensiDetail::where('absensi_id', $absensi->id)
                                    ->where('santri_id', $santriId)
                                    ->first();

        if ($existingDetail && in_array($existingDetail->status, ['Hadir', 'Izin', 'Sakit'])) {
            $this->status = 'error';
            $this->message = 'Status kehadiran Anda sudah tercatat sebagai "' . $existingDetail->status . '" dan tidak dapat diubah melalui scan.';
            return;
        }

        $pernahScan = ScanLog::where('qr_session_id', $qrSession->id)
            ->where('santri_id', $santriId)->exists();
            
        if($pernahScan) {
            $this->status = 'error';
            $this->message = 'Anda sudah melakukan scan dengan QR Code ini.';
            return;
        }
        
        ScanLog::create([
            'qr_session_id' => $qrSession->id,
            'santri_id' => $santriId
        ]);

        AbsensiDetail::updateOrCreate(
            ['absensi_id' => $absensi->id, 'santri_id' => $santriId],
            ['status' => 'Hadir', 'jam_hadir' => now()]
        );
        
        $this->status = 'success';
        $this->message = 'Absensi berhasil! Anda tercatat hadir untuk mata pelajaran ' . $jadwal->mata_pelajaran . '.';
        $this->dispatch('scanUpdated');
    }

    public function render()
    {
        return view('livewire.absensi.scan');
    }
}