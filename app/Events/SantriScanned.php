<?php

namespace App\Events;

use App\Models\AbsensiDetail;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SantriScanned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // Properti publik ini akan otomatis terkirim sebagai data event
    public AbsensiDetail $absensiDetail;

    public function __construct(AbsensiDetail $absensiDetail)
    {
        $this->absensiDetail = $absensiDetail;
    }

    /**
     * Tentukan channel broadcast.
     * Kita menggunakan PrivateChannel agar hanya admin yang bisa mendengarkan.
     * Channel-nya spesifik per sesi absensi (absensi.1, absensi.2, dst).
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('absensi.' . $this->absensiDetail->absensi_id),
        ];
    }
}