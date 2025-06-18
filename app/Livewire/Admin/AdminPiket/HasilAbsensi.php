<?php

namespace App\Livewire\Admin\AdminPiket;

use App\Models\Absensi\Absensi;
use App\Models\Absensi\AbsensiDetail;
use App\Models\ESantri\JadwalPelajaran;
use App\Models\Santri;
use Carbon\Carbon;
use Livewire\Component;

class HasilAbsensi extends Component
{
    public JadwalPelajaran $jadwal;
    public $tanggal;
    public $absensi;
    public array $statusKehadiran = [];
    public $semuaSantri;

    public function mount($jadwalId, $tanggal)
    {
        $this->jadwal = JadwalPelajaran::with('kelas')->findOrFail($jadwalId);
        $this->tanggal = $tanggal;
        $this->semuaSantri = Santri::where('kelas_id', $this->jadwal->kelas_id)->orderBy('nama')->get();

        $this->absensi = Absensi::where('jadwal_pelajaran_id', $this->jadwal->id)
            ->where('tanggal', $this->tanggal)
            ->first();

        if ($this->absensi) {
            $details = AbsensiDetail::where('absensi_id', $this->absensi->id)->get();
            foreach ($this->semuaSantri as $santri) {
                $detail = $details->firstWhere('santri_id', $santri->id);
                $this->statusKehadiran[$santri->id] = $detail ? $detail->status : 'Alpa';
            }
        } else {
            foreach ($this->semuaSantri as $santri) {
                $this->statusKehadiran[$santri->id] = 'Alpa';
            }
        }
    }

    public function updateStatus($santriId, $status)
    {
        if (!$this->absensi) {
            $this->absensi = Absensi::create([
                'jadwal_pelajaran_id' => $this->jadwal->id,
                'kelas_id' => $this->jadwal->kelas_id,
                'tanggal' => $this->tanggal,
            ]);
        }
        
        $detail = AbsensiDetail::where('absensi_id', $this->absensi->id)
            ->where('santri_id', $santriId)
            ->first();

        if ($detail) {
            $detail->update([
                'status' => $status,
                'jam_hadir' => ($status === 'Hadir' && is_null($detail->jam_hadir)) ? now() : null
            ]);
        } else {
            AbsensiDetail::create([
                'absensi_id' => $this->absensi->id,
                'santri_id' => $santriId,
                'status' => $status,
                'jam_hadir' => $status === 'Hadir' ? now() : null,
            ]);
        }
        
        $this->statusKehadiran[$santriId] = $status;
    }

    public function render()
    {
        return view('livewire.admin.admin-piket.hasil-absensi', [
            'tanggalFormatted' => Carbon::parse($this->tanggal)->translatedFormat('l, d F Y')
        ]);
    }
}