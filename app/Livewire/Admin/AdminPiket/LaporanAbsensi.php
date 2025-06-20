<?php

namespace App\Livewire\Admin\AdminPiket;

use App\Exports\AbsensiHarianExport;
use App\Exports\AbsensiRekapExport;
use App\Models\Kelas;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Maatwebsite\Excel\Facades\Excel;

class LaporanAbsensi extends Component
{
    #[Title('Laporan Absensi')]
    #[Layout('components.layouts.app')]

    public $selectedDate;
    public $selectedMonth;
    public $selectedYear;
    public $filterKelasId = '';
    public $listKelas;

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->selectedMonth = now()->format('Y-m');
        $this->selectedYear = now()->format('Y');
        $this->listKelas = Kelas::orderBy('nama')->get();
    }

    public function exportHarian()
    {
        $this->validate(['selectedDate' => 'required|date']);
        $date = Carbon::parse($this->selectedDate)->format('d-m-Y');
        return Excel::download(new AbsensiHarianExport($this->selectedDate), "absensi-harian-{$date}.xlsx");
    }

    public function exportBulanan()
    {
        $this->validate(['selectedMonth' => 'required']);
        $date = Carbon::parse($this->selectedMonth);
        $startDate = $date->startOfMonth()->format('Y-m-d');
        $endDate = $date->endOfMonth()->format('Y-m-d');

        $fileName = "rekap-absensi-bulanan-{$date->format('F-Y')}.xlsx";
        if ($this->filterKelasId) {
            $kelas = Kelas::find($this->filterKelasId);
            $fileName = "rekap-absensi-{$kelas->nama}-{$date->format('F-Y')}.xlsx";
        }

        return Excel::download(new AbsensiRekapExport($startDate, $endDate, $this->filterKelasId), $fileName);
    }

    public function exportTahunan()
    {
        $this->validate(['selectedYear' => 'required|numeric']);
        $date = Carbon::createFromDate($this->selectedYear);
        $startDate = $date->startOfYear()->format('Y-m-d');
        $endDate = $date->endOfYear()->format('Y-m-d');

        $fileName = "rekap-absensi-tahunan-{$this->selectedYear}.xlsx";
        if ($this->filterKelasId) {
            $kelas = Kelas::find($this->filterKelasId);
            $fileName = "rekap-absensi-{$kelas->nama}-{$this->selectedYear}.xlsx";
        }
        
        return Excel::download(new AbsensiRekapExport($startDate, $endDate, $this->filterKelasId), $fileName);
    }

    public function exportSeluruh()
    {
        $fileName = "rekap-absensi-keseluruhan.xlsx";
        if ($this->filterKelasId) {
            $kelas = Kelas::find($this->filterKelasId);
            $fileName = "rekap-absensi-keseluruhan-{$kelas->nama}.xlsx";
        }

        return Excel::download(new AbsensiRekapExport(null, null, $this->filterKelasId), $fileName);
    }

    public function render()
    {
        return view('livewire.admin.admin-piket.laporan-absensi');
    }
}