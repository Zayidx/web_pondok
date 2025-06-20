<?php

namespace App\Livewire\Admin\AdminPiket;

use App\Exports\AbsensiHarianExport;
use App\Models\Absensi\Absensi;
use App\Models\Absensi\AbsensiDetail;
use App\Models\ESantri\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Santri;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Dashboard extends Component
{

    #[Title('')]
    public $selectedDate;

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
    }

    public function exportExcel()
    {
        $fileName = 'laporan-kehadiran-harian-' . $this->selectedDate . '.xlsx';
        return Excel::download(new AbsensiHarianExport($this->selectedDate), $fileName);
    }
      
    public function render()
    {
        Carbon::setLocale('id');
        $date = Carbon::parse($this->selectedDate);
        $dayName = $date->translatedFormat('l');

        $schedules = JadwalPelajaran::with('kelas')
            ->where('hari', $dayName)
            ->orderBy('waktu_mulai', 'asc')
            ->get();
        
        $kelasIds = $schedules->pluck('kelas_id')->unique()->filter();

        $uniqueAttendanceDetails = collect();
        if ($kelasIds->isNotEmpty()) {
            $students = Santri::with('kelas')->whereIn('kelas_id', $kelasIds)->get();

            $absensiIds = Absensi::where('tanggal', $this->selectedDate)
                            ->whereIn('jadwal_pelajaran_id', $schedules->pluck('id'))
                            ->pluck('id');

            $allDetails = AbsensiDetail::whereIn('absensi_id', $absensiIds)->get()->keyBy('santri_id');
            $absensiRecords = Absensi::whereIn('id', $absensiIds)->get()->keyBy('jadwal_pelajaran_id');

            foreach ($students as $student) {
                $firstSchedule = $schedules->where('kelas_id', $student->kelas_id)->first();
                if (!$firstSchedule) continue;
                
                $firstAbsensi = $absensiRecords->get($firstSchedule->id);

                $status = 'Alpa';
                if ($firstAbsensi) {
                    $detail = $allDetails->get($student->id);
                    if ($detail && $detail->absensi_id == $firstAbsensi->id) {
                        $status = $detail->status;
                    }
                }
                
                $uniqueAttendanceDetails->push((object)[
                    'status' => $status,
                    'kelas_id' => $student->kelas_id,
                    'kelas_nama' => $student->kelas->nama,
                ]);
            }
        }
        
        $totalHadir = $uniqueAttendanceDetails->where('status', 'Hadir')->count();
        $totalSakit = $uniqueAttendanceDetails->where('status', 'Sakit')->count();
        $totalIzin = $uniqueAttendanceDetails->where('status', 'Izin')->count();
        $totalAlpa = $uniqueAttendanceDetails->where('status', 'Alpa')->count();

        $kehadiranPerKelas = $uniqueAttendanceDetails->groupBy('kelas_nama');
        
        $chartLabels = $kehadiranPerKelas->keys();
        $chartSeries = [
            ['name' => 'Hadir', 'data' => []],
            ['name' => 'Sakit', 'data' => []],
            ['name' => 'Izin', 'data' => []],
            ['name' => 'Alpa', 'data' => []],
        ];

        foreach ($chartLabels as $kelasNama) {
            $detailsForClass = $kehadiranPerKelas[$kelasNama];
            $chartSeries[0]['data'][] = $detailsForClass->where('status', 'Hadir')->count();
            $chartSeries[1]['data'][] = $detailsForClass->where('status', 'Sakit')->count();
            $chartSeries[2]['data'][] = $detailsForClass->where('status', 'Izin')->count();
            $chartSeries[3]['data'][] = $detailsForClass->where('status', 'Alpa')->count();
        }

        $groupedJadwal = [];
        foreach ($schedules as $jadwal) {
            $kelasId = $jadwal->kelas_id;
            if (!$kelasId) continue;
            $namaKelas = $jadwal->kelas->nama ?? 'Tanpa Kelas';
            if (!isset($groupedJadwal[$kelasId])) {
                $groupedJadwal[$kelasId] = ['kelas_id' => $kelasId, 'kelas_nama' => $namaKelas, 'jadwals' => []];
            }
            $groupedJadwal[$kelasId]['jadwals'][] = $jadwal;
        }

        foreach ($groupedJadwal as $kelasId => &$data) {
            $waktuMulai = $schedules->where('kelas_id', $kelasId)->min('waktu_mulai');
            $waktuSelesai = $schedules->where('kelas_id', $kelasId)->max('waktu_selesai');
            $data['jadwal_masuk'] = $waktuMulai ? Carbon::parse($waktuMulai)->format('H:i') : '-';
            $data['jadwal_pulang'] = $waktuSelesai ? Carbon::parse($waktuSelesai)->format('H:i') : '-';
            $data['total_mapel'] = count($data['jadwals']);
        }

        return view('livewire.admin.admin-piket.dashboard', [
            'tanggalDipilihFormatted' => $date->translatedFormat('d F Y'),
            'hariDipilih'             => $dayName,
            'groupedJadwal'           => $groupedJadwal,
            'totalHadir'              => $totalHadir,
            'totalSakit'              => $totalSakit,
            'totalIzin'               => $totalIzin,
            'totalAlpa'               => $totalAlpa,
            'chartLabels'             => $chartLabels,
            'chartSeries'             => $chartSeries,
        ]);
    }
}