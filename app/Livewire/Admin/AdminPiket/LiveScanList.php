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
    
    
    #[On('qrSessionCreated')]
    public function updateQrSessionId($qrSessionId)
    {
        $this->qrSessionId = $qrSessionId;
        
        $this->liveScans = collect();
    }
    
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
