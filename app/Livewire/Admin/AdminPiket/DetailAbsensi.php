<?php

namespace App\Livewire\Admin\AdminPiket;

use App\Models\Kelas;
use App\Models\ESantri\JadwalPelajaran;
use Carbon\Carbon;
use Livewire\Component;

class DetailAbsensi extends Component
{
    public $kelasId;
    public $tanggal; // Menerima tanggal dari URL
    public $isToday; // Penanda apakah tanggal yang dipilih adalah hari ini

    public $kelas;
    public $jadwalKelasHariIni;

    public function mount($kelasId, $tanggal)
    {
        $this->kelasId = $kelasId;
        $this->tanggal = $tanggal;

        // Cek apakah tanggal yang dipilih sama dengan tanggal hari ini
        $this->isToday = Carbon::parse($this->tanggal)->isToday();

        $this->loadData();
    }

    public function loadData()
    {
        Carbon::setLocale('id');
        // Menggunakan tanggal yang diterima untuk mencari hari
        $selectedDate = Carbon::parse($this->tanggal);
        $hariDipilih = $selectedDate->translatedFormat('l');

        $this->kelas = Kelas::with(['jenjang'])->find($this->kelasId);

        if (!$this->kelas) {
            // Memberi pesan error jika kelas tidak ditemukan
            session()->flash('error', 'Kelas tidak ditemukan.');
            return redirect()->route('admin.piket.dashboard');
        }

        // Mencari jadwal pelajaran berdasarkan hari yang sudah ditentukan
        $this->jadwalKelasHariIni = JadwalPelajaran::with(['kategoriPelajaran'])
            ->where('kelas_id', $this->kelasId)
            ->where('hari', $hariDipilih)
            ->orderBy('waktu_mulai')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.admin-piket.detail-absensi', [
            // Mengirimkan format tanggal ke view untuk ditampilkan
            'tanggalFormatted' => Carbon::parse($this->tanggal)->translatedFormat('d F Y')
        ]);
    }
}
