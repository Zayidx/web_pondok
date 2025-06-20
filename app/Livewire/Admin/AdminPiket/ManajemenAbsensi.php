<?php

namespace App\Livewire\Admin\AdminPiket;

use App\Models\Absensi\Absensi;
use App\Models\Absensi\AbsensiDetail;
use Livewire\Component;
use App\Models\ESantri\JadwalPelajaran;
use Carbon\Carbon;

class ManajemenAbsensi extends Component
{
    public function perbaruiStatusAbsensi($absensiId, $santriId, $status, $jadwal = null, $tanggal = null)
    {
        if (!$absensiId && $jadwal && $tanggal) {
            $absensi = Absensi::firstOrCreate(
                [
                    'jadwal_pelajaran_id' => $jadwal->id,
                    'kelas_id' => $jadwal->kelas_id,
                    'tanggal' => $tanggal,
                ]
            );
            $absensiId = $absensi->id;
        }

        if (!$absensiId) {
            return null;
        }

        return AbsensiDetail::updateOrCreate(
            ['absensi_id' => $absensiId, 'santri_id' => $santriId],
            [
                'status' => $status,
                'jam_hadir' => ($status === 'Hadir') ? now() : null,
            ]
        );
    }
}