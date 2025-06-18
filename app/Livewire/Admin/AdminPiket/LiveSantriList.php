<?php

namespace App\Livewire\Admin\AdminPiket;

use App\Models\Absensi\AbsensiDetail;
use App\Models\Santri;
use Illuminate\Support\Collection;
use Livewire\Component;

class LiveSantriList extends Component
{
    public $absensiId;
    public $kelasId;

    public Collection $semuaSantri;
    public Collection $daftarKehadiran;
    public int $jumlahHadir = 0;
    public int $totalSantri = 0;

    /**
     * Dijalankan saat komponen pertama kali dimuat.
     * Menerima ID absensi dan ID kelas dari komponen induk.
     */
    public function mount($absensiId, $kelasId)
    {
        $this->absensiId = $absensiId;
        $this->kelasId = $kelasId;

        // Mengambil daftar semua santri di kelas ini sekali saja.
        $this->semuaSantri = Santri::where('kelas_id', $this->kelasId)->orderBy('nama')->get();
        $this->totalSantri = $this->semuaSantri->count();
        
        // Memuat data kehadiran awal.
        $this->loadKehadiran();
    }

    /**
     * Method ini akan dipanggil oleh wire:poll setiap 5 detik.
     * Tugasnya adalah mengambil data kehadiran terbaru dari database.
     */
    public function loadKehadiran()
    {
        // Mengambil semua detail absensi untuk sesi ini.
        $this->daftarKehadiran = AbsensiDetail::where('absensi_id', $this->absensiId)
            ->get()
            ->keyBy('santri_id');
        
        // Menghitung ulang jumlah santri yang hadir.
        $this->jumlahHadir = $this->daftarKehadiran->where('status', 'Hadir')->count();
    }

    /**
     * Dijalankan saat admin menekan tombol status secara manual.
     */
    public function updateStatus($santriId, $status)
    {
        AbsensiDetail::updateOrCreate(
            ['absensi_id' => $this->absensiId, 'santri_id' => $santriId],
            [
                'status' => $status,
                'jam_hadir' => ($status === 'Hadir') ? now() : null,
            ]
        );

        // Setelah update, langsung panggil loadKehadiran agar UI langsung berubah
        // tanpa harus menunggu siklus polling berikutnya.
        $this->loadKehadiran();
    }

    /**
     * Merender file view untuk komponen ini.
     */
    public function render()
    {
        return view('livewire.admin.admin-piket.live-santri-list');
    }
}
