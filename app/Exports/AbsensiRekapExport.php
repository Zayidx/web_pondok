<?php

namespace App\Exports;

use App\Models\Santri;
use App\Models\Absensi\Absensi;
use App\Models\Absensi\AbsensiDetail;
use App\Models\ESantri\JadwalPelajaran;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiRekapExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $kelasId;

    public function __construct($startDate = null, $endDate = null, $kelasId = null)
    {
        $this->startDate = $startDate ? Carbon::parse($startDate) : null;
        $this->endDate = $endDate ? Carbon::parse($endDate) : null;
        $this->kelasId = $kelasId;
    }

    public function collection()
    {
        Carbon::setLocale('id');

        $studentsQuery = Santri::with('kelas');
        if ($this->kelasId) {
            $studentsQuery->where('kelas_id', $this->kelasId);
        }
        $students = $studentsQuery->orderBy('kelas_id')->orderBy('nama')->get();

        $finalCounts = [];
        foreach ($students as $student) {
            $finalCounts[$student->id] = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpa' => 0];
        }

        $actualStartDate = $this->startDate;
        $actualEndDate = $this->endDate;

        if (!$actualStartDate || !$actualEndDate) {
            $minDate = Absensi::min('tanggal');
            $maxDate = Absensi::max('tanggal');

            if (!$minDate || !$maxDate) {
                return collect();
            }

            $actualStartDate = Carbon::parse($minDate);
            $actualEndDate = Carbon::parse($maxDate);
        }
        
        $period = CarbonPeriod::create($actualStartDate, $actualEndDate);
        $allSchedules = JadwalPelajaran::orderBy('waktu_mulai', 'asc')->get();

        foreach ($period as $date) {
            $dayName = $date->translatedFormat('l');

            $firstSchedules = $allSchedules->where('hari', $dayName)
                ->groupBy('kelas_id')
                ->map(function ($schedules) {
                    return $schedules->first();
                });
            
            $firstScheduleIds = $firstSchedules->pluck('id');

            if ($firstScheduleIds->isEmpty()) {
                continue;
            }
            
            $absensiForDay = Absensi::where('tanggal', $date->format('Y-m-d'))
                ->whereIn('jadwal_pelajaran_id', $firstScheduleIds)
                ->get()
                ->keyBy('jadwal_pelajaran_id');

            $absensiIdsForDay = $absensiForDay->pluck('id');
            
            $detailsForDay = collect();
            if ($absensiIdsForDay->isNotEmpty()) {
                $detailsForDay = AbsensiDetail::whereIn('absensi_id', $absensiIdsForDay)
                    ->get()
                    ->keyBy('santri_id');
            }
            
            foreach ($students as $student) {
                $firstScheduleForClass = $firstSchedules->get($student->kelas_id);
                
                if (!$firstScheduleForClass) {
                    continue;
                }

                $absensiRecord = $absensiForDay->get($firstScheduleForClass->id);
                $detail = $absensiRecord ? $detailsForDay->get($student->id) : null;
                
                $status = 'Alpa';
                if ($detail && $detail->absensi_id == $absensiRecord->id) {
                    $status = $detail->status;
                }

                if (isset($finalCounts[$student->id][$status])) {
                    $finalCounts[$student->id][$status]++;
                }
            }
        }

        $exportData = collect();
        foreach ($students as $student) {
            $counts = $finalCounts[$student->id] ?? ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpa' => 0];
            $totalAbsen = ($counts['Izin'] ?? 0) + ($counts['Sakit'] ?? 0) + ($counts['Alpa'] ?? 0);

            $exportData->push([
                'nisn' => $student->nisn ?? '-',
                'nama' => $student->nama,
                'kelas' => optional($student->kelas)->nama ?? 'N/A',
                'hadir' => $counts['Hadir'] ?? 0,
                'sakit' => $counts['Sakit'] ?? 0,
                'izin' => $counts['Izin'] ?? 0,
                'alpa' => $counts['Alpa'] ?? 0,
                'total_absen' => $totalAbsen
            ]);
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'NISN',
            'Nama Santri',
            'Kelas',
            'Jumlah Hadir',
            'Jumlah Sakit',
            'Jumlah Izin',
            'Jumlah Alpa',
            'Total Tidak Hadir'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}