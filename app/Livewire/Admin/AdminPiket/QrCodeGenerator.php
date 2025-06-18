<?php

namespace App\Livewire\Admin\AdminPiket;

use App\Models\Absensi\QrSession;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class QrCodeGenerator extends Component
{
    public $absensiId;
    public ?string $qrCodeUrl = null;
    public $sessionExpiresAt = null;

    public function mount($absensiId)
    {
        $this->absensiId = $absensiId;
    }

    public function generateNewQrCode()
    {
        Log::info('--- [QrCodeGenerator] Aksi generateNewQrCode dimulai ---');
        try {
            QrSession::where('absensi_id', $this->absensiId)->delete();
            Log::info('[QrCodeGenerator] Sesi QR lama telah dihapus.');

            $token = Str::random(40);
            $expiryTime = now()->addMinutes(5);

            $qrSession = QrSession::create([
                'absensi_id' => $this->absensiId,
                'token'      => $token,
                'expires_at' => $expiryTime,
            ]);
            
            $this->sessionExpiresAt = $expiryTime->toIso8601String();
            $this->qrCodeUrl = route('santri.absensi.scan', ['token' => $token]);

        
            $this->dispatch('qrSessionCreated', qrSessionId: $qrSession->id);

            Log::info('[QrCodeGenerator] Sesi QR baru dibuat dengan ID: ' . $qrSession->id);
        } catch (\Exception $e) {
            Log::error('[QrCodeGenerator] Terjadi error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        if ($this->sessionExpiresAt && now()->greaterThan($this->sessionExpiresAt)) {
            $this->qrCodeUrl = null;
        }
        return view('livewire.admin.admin-piket.qr-code-generator');
    }
}
