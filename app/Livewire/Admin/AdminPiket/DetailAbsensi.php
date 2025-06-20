<?php

namespace App\Livewire\Admin\AdminPiket;

use App\Models\Kelas;
use App\Models\ESantri\JadwalPelajaran;
use Carbon\Carbon;
use Livewire\Attributes\Title;
use Livewire\Component;

class DetailAbsensi extends Component
{
    #[Title('')]
    public $kelasId;
    public $tanggal;
    public $isToday;

    public $kelas;
    public $jadwalKelasHariIni;

    public function mount($kelasId, $tanggal)
    {
        $this->kelasId = $kelasId;
        $this->tanggal = $tanggal;
        $this->isToday = Carbon::parse($this->tanggal)->isToday();
        $this->loadData();
    }

    public function loadData()
    {
        Carbon::setLocale('id');
        $selectedDate = Carbon::parse($this->tanggal);
        $hariDipilih = $selectedDate->translatedFormat('l');

        $this->kelas = Kelas::with(['jenjang'])->find($this->kelasId);

        if (!$this->kelas) {
            session()->flash('error', 'Kelas tidak ditemukan.');
            return redirect()->route('admin.piket.dashboard');
        }

        $this->jadwalKelasHariIni = JadwalPelajaran::with(['kategoriPelajaran'])
            ->where('kelas_id', $this->kelasId)
            ->where('hari', $hariDipilih)
            ->orderBy('waktu_mulai')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.admin-piket.detail-absensi', [
            'tanggalFormatted' => Carbon::parse($this->tanggal)->translatedFormat('d F Y')
        ]);
    }
}