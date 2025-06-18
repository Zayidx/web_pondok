<?php

namespace App\Livewire\Admin\AdminPiket;

use App\Models\Absensi\ScanLog;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class LiveScanList extends Component
{
    public $absensiId;
    public ?int $qrSessionId = null;
    public Collection $liveScans;

    public function mount($absensiId)
    {
        $this->absensiId = $absensiId;
        $this->liveScans = collect();
    }
    
    // Komentar: Method ini akan berjalan ketika menerima event 'qrSessionCreated'
    #[On('qrSessionCreated')]
    public function updateQrSessionId($qrSessionId)
    {
        $this->qrSessionId = $qrSessionId;
        // Komentar: Kosongkan daftar scan lama saat QR baru dibuat.
        $this->liveScans = collect();
    }
    
    // Komentar: Method ini juga akan berjalan ketika menerima event 'scanUpdated' dari parent
    #[On('scanUpdated')]
    public function checkScanStatus()
    {
        if ($this->qrSessionId) {
            $this->liveScans = ScanLog::where('qr_session_id', $this->qrSessionId)
                ->with('santri')
                ->latest()
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.admin.admin-piket.live-scan-list');
    }
}
