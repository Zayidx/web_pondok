<?php

namespace App\Livewire\Admin\AdminPiket;

use App\Models\Absensi\Absensi;
use App\Models\Absensi\AbsensiDetail;
use App\Models\ESantri\JadwalPelajaran;
use App\Models\Santri;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

// [PERUBAHAN] Komponen ini menjadi lebih sederhana dan hanya mengontrol data utama.
class AbsenMurid extends Component
{

    #[Layout('components/layouts/app')]
    public JadwalPelajaran $jadwal;
    public Collection $semuaSantri;
    public $absensiId;
    public Collection $daftarKehadiran;
    public int $jumlahHadir = 0;
    public int $totalSantri = 0;

    // Komentar: Properti yang berhubungan dengan QR dan Scan Log telah dipindahkan ke komponen anak.

    public function mount($jadwalId)
    {
        $this->jadwal = JadwalPelajaran::with('kelas')->findOrFail($jadwalId);
        $this->daftarKehadiran = collect();

        $absensi = Absensi::firstOrCreate(
            ['tanggal' => now()->format('Y-m-d'), 'jadwal_pelajaran_id' => $this->jadwal->id],
            ['kelas_id' => $this->jadwal->kelas_id]
        );
        $this->absensiId = $absensi->id;
        
        $this->semuaSantri = Santri::where('kelas_id', $this->jadwal->kelas_id)->orderBy('nama')->get();
        $this->totalSantri = $this->semuaSantri->count();
        $this->loadKehadiran();
    }

    public function loadKehadiran()
    {
        $this->daftarKehadiran = AbsensiDetail::where('absensi_id', $this->absensiId)
                                    ->get()
                                    ->keyBy('santri_id');
        $this->hitungJumlahHadir();
    }

    public function hitungJumlahHadir()
    {
        $this->jumlahHadir = $this->daftarKehadiran->where('status', 'Hadir')->count();
    }

    public function updateStatus($santriId, $status)
    {
        AbsensiDetail::updateOrCreate(
            ['absensi_id' => $this->absensiId, 'santri_id' => $santriId],
            [
                'status' => $status,
                'jam_hadir' => ($status === 'Hadir') ? now() : null,
            ]
        );
        // Komentar: Setelah update, kita perlu memuat ulang data di komponen ini.
        $this->loadKehadiran();
        // Komentar: Dan juga memberi tahu komponen daftar scan untuk refresh.
        $this->dispatch('scanUpdated');
    }

    public function render()
    {
        // Komentar: Method render sekarang hanya bertugas menampilkan view utama.
        return view('livewire.admin.admin-piket.absen-murid');
    }
}
