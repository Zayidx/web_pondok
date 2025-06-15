<?php

namespace App\Services\PSB;

use Carbon\Carbon;
use App\Models\PSB\Wawancara;
use App\Services\PSB\NotificationService;

class WawancaraService
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function scheduleWawancara($santriId, $tanggal, $jam, $mode, $link = null)
    {
        try {
            // Validasi tanggal dan jam
            $jadwalWawancara = Carbon::parse($tanggal . ' ' . $jam);
            
            if ($jadwalWawancara->isPast()) {
                throw new \Exception('Jadwal wawancara tidak boleh di masa lalu');
            }

            // Cek bentrok jadwal
            if ($this->isJadwalBentrok($jadwalWawancara)) {
                throw new \Exception('Jadwal wawancara bentrok dengan jadwal lain');
            }

            // Validasi mode wawancara
            if ($mode === 'online' && empty($link)) {
                throw new \Exception('Link meeting harus diisi untuk wawancara online');
            }

            // Buat jadwal wawancara
            $wawancara = Wawancara::create([
                'santri_id' => $santriId,
                'tanggal_wawancara' => $tanggal,
                'jam_wawancara' => $jam,
                'mode_wawancara' => $mode,
                'link_meeting' => $link,
                'status' => 'pending'
            ]);

            // Kirim notifikasi
            $this->sendWawancaraNotification($wawancara);

            return $wawancara;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function isJadwalBentrok($jadwalWawancara)
    {
        $startTime = $jadwalWawancara->copy()->subMinutes(30);
        $endTime = $jadwalWawancara->copy()->addMinutes(30);

        return Wawancara::whereBetween('jam_wawancara', [$startTime, $endTime])
            ->where('tanggal_wawancara', $jadwalWawancara->toDateString())
            ->exists();
    }

    private function sendWawancaraNotification($wawancara)
    {
        $data = [
            'nama_santri' => $wawancara->santri->nama_lengkap,
            'tanggal' => $wawancara->tanggal_wawancara,
            'jam' => $wawancara->jam_wawancara,
            'mode' => $wawancara->mode_wawancara,
            'link' => $wawancara->link_meeting
        ];

        // Kirim email
        $this->notificationService->sendEmail(
            $wawancara->santri->email,
            'Jadwal Wawancara PSB',
            'wawancara_scheduled',
            $data
        );

        // Kirim WhatsApp
        $message = "Jadwal wawancara Anda telah diatur:\n" .
                  "Tanggal: {$wawancara->tanggal_wawancara}\n" .
                  "Jam: {$wawancara->jam_wawancara}\n" .
                  "Mode: {$wawancara->mode_wawancara}\n" .
                  ($wawancara->link_meeting ? "Link: {$wawancara->link_meeting}" : "");

        $this->notificationService->sendWhatsApp(
            $wawancara->santri->no_hp,
            $message
        );
    }

    public function handleOnlineWawancaraFailure($wawancaraId)
    {
        try {
            $wawancara = Wawancara::findOrFail($wawancaraId);
            
            // Update status
            $wawancara->update([
                'status' => 'batal',
                'catatan' => 'Wawancara dibatalkan karena masalah teknis'
            ]);

            // Kirim notifikasi pembatalan
            $this->sendCancellationNotification($wawancara);

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function sendCancellationNotification($wawancara)
    {
        $data = [
            'nama_santri' => $wawancara->santri->nama_lengkap,
            'tanggal' => $wawancara->tanggal_wawancara,
            'jam' => $wawancara->jam_wawancara,
            'alasan' => 'masalah teknis'
        ];

        // Kirim email
        $this->notificationService->sendEmail(
            $wawancara->santri->email,
            'Pembatalan Wawancara PSB',
            'wawancara_cancelled',
            $data
        );

        // Kirim WhatsApp
        $message = "Mohon maaf, wawancara Anda pada:\n" .
                  "Tanggal: {$wawancara->tanggal_wawancara}\n" .
                  "Jam: {$wawancara->jam_wawancara}\n" .
                  "Terpaksa dibatalkan karena masalah teknis.\n" .
                  "Kami akan menghubungi Anda untuk jadwal baru.";

        $this->notificationService->sendWhatsApp(
            $wawancara->santri->no_hp,
            $message
        );
    }
} 