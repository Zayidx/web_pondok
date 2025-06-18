<?php

namespace App\Livewire\Admin\AdminPiket;

use Livewire\Component;
use App\Models\ESantri\JadwalPelajaran;
use Carbon\Carbon;

class ManajemenAbsensi extends Component
{
    public $hariIni;

    public function mount()
    {
        $this->hariIni = Carbon::now()->locale('id')->dayName;
    }

    public function render()
    {
        $jadwalPelajaranHariIni = JadwalPelajaran::where('hari', $this->hariIni)
            ->with(['kelas', 'kategoriPelajaran'])
            ->orderBy('waktu_mulai')
            ->get();

        return view('livewire.admin.admin-piket.manajemen-absensi', [
            'jadwalPelajaran' => $jadwalPelajaranHariIni,
        ]);
    }
}