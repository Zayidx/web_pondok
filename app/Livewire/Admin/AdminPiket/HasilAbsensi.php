<?php

namespace App\Livewire\Admin\AdminPiket;

use App\Models\Absensi;
use App\Models\AbsensiDetail;
use App\Models\ESantri\JadwalPelajaran;
use Carbon\Carbon;
use Livewire\Component;

class HasilAbsensi extends Component
{
    public JadwalPelajaran $jadwal;
    public $tanggal;
    public $absensi;
    public $detailKehadiran = [];

    public function mount($jadwalId, $tanggal)
    {
        $this->jadwal = JadwalPelajaran::with('kelas')->findOrFail($jadwalId);
        $this->tanggal = $tanggal;

        // Mencari data absensi berdasarkan jadwal dan tanggal
        $this->absensi = Absensi::where('jadwal_pelajaran_id', $this->jadwal->id)
            ->where('tanggal', $this->tanggal)
            ->first();

        // Jika data absensi ditemukan, muat detailnya
        if ($this->absensi) {
            $details = AbsensiDetail::with('santri')
                ->where('absensi_id', $this->absensi->id)
                ->get()
                ->keyBy('santri_id'); // Menggunakan santri_id sebagai kunci array

            // Memasukkan data ke properti untuk ditampilkan di view
            foreach ($details as $santriId => $detail) {
                $this->detailKehadiran[$santriId] = [
                    'nama' => $detail->santri->nama,
                    'status' => $detail->status,
                ];
            }
        }
    }

    /**
     * Mengupdate status kehadiran santri di database.
     */
    public function updateStatus($santriId, $status)
    {
        if ($this->absensi) {
            AbsensiDetail::where('absensi_id', $this->absensi->id)
                ->where('santri_id', $santriId)
                ->update([
                    'status' => $status,
                    'jam_hadir' => ($status === 'Hadir' && is_null(AbsensiDetail::where('absensi_id', $this->absensi->id)->where('santri_id', $santriId)->first()->jam_hadir)) ? now() : AbsensiDetail::where('absensi_id', $this->absensi->id)->where('santri_id', $santriId)->first()->jam_hadir,
                ]);
            
            // Memperbarui tampilan di frontend secara langsung
            $this->detailKehadiran[$santriId]['status'] = $status;
        }
    }

    public function render()
    {
        return view('livewire.admin.admin-piket.hasil-absensi', [
            'tanggalFormatted' => Carbon::parse($this->tanggal)->translatedFormat('l, d F Y')
        ]);
    }
}
