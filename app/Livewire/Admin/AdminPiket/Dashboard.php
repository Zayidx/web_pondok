<?php

namespace App\Livewire\Admin\AdminPiket;

use App\Exports\AbsensiHarianExport;
use App\Models\Absensi\AbsensiDetail;
use App\Models\ESantri\JadwalPelajaran;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Dashboard extends Component
{
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
            ->get();

        $totalKelasHariIni = $schedules->pluck('kelas_id')->unique()->count();

        $absensiIds = \App\Models\Absensi\Absensi::where('tanggal', $date->format('Y-m-d'))
            ->whereIn('jadwal_pelajaran_id', $schedules->pluck('id'))
            ->pluck('id');

        $attendanceDetailsToday = AbsensiDetail::whereIn('absensi_id', $absensiIds)->get();

        $totalHadir = $attendanceDetailsToday->where('status', 'Hadir')->count();
        $totalSakit = $attendanceDetailsToday->where('status', 'Sakit')->count();
        $totalIzin = $attendanceDetailsToday->where('status', 'Izin')->count();
        
        $totalExpectedAttendances = $schedules->sum(function($schedule) {
            return $schedule->kelas->jumlah_santri ?? 0;
        });
        $totalAlpa = $totalExpectedAttendances - ($totalHadir + $totalSakit + $totalIzin);
        if ($totalAlpa < 0) $totalAlpa = 0;

        $kehadiranPerKelas = AbsensiDetail::with('absensi.jadwalPelajaran.kelas')
            ->whereIn('absensi_id', $absensiIds)
            ->where('status', 'Hadir')
            ->get()
            ->groupBy(function($detail) {
                return $detail->absensi->jadwalPelajaran->kelas->nama ?? 'Tanpa Kelas';
            })
            ->map(function($group) {
                return $group->count();
            });

        $chartLabels = $kehadiranPerKelas->keys();
        $chartData = $kehadiranPerKelas->values();

        $groupedJadwal = [];
        foreach ($schedules as $jadwal) {
            $kelasId = $jadwal->kelas_id;
            if (!$kelasId) continue;

            $namaKelas = $jadwal->kelas->nama ?? 'Tanpa Kelas';
            if (!isset($groupedJadwal[$kelasId])) {
                $groupedJadwal[$kelasId] = [
                    'kelas_id'   => $kelasId,
                    'kelas_nama' => $namaKelas,
                    'jadwals'    => [],
                ];
            }
            $groupedJadwal[$kelasId]['jadwals'][] = $jadwal;
        }

        foreach ($groupedJadwal as $kelasId => $data) {
            $waktuMulai = $schedules->where('kelas_id', $kelasId)->min('waktu_mulai');
            $waktuSelesai = $schedules->where('kelas_id', $kelasId)->max('waktu_selesai');

            $groupedJadwal[$kelasId]['jadwal_masuk'] = $waktuMulai ? Carbon::parse($waktuMulai)->format('H:i') : '-';
            $groupedJadwal[$kelasId]['jadwal_pulang'] = $waktuSelesai ? Carbon::parse($waktuSelesai)->format('H:i') : '-';
            $groupedJadwal[$kelasId]['total_mapel'] = count($data['jadwals']);
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
            'chartData'               => $chartData,
        ]);
    }
}
