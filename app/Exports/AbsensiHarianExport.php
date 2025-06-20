<?php

namespace App\Exports;

use App\Models\Absensi\AbsensiDetail;
use App\Models\ESantri\JadwalPelajaran;
use App\Models\Santri;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiHarianExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $selectedDate;

    public function __construct($selectedDate)
    {
        $this->selectedDate = $selectedDate;
    }

    public function collection()
    {
        $date = Carbon::parse($this->selectedDate);
        $dayName = $date->translatedFormat('l');

        $schedules = JadwalPelajaran::with(['kelas', 'absensi' => function($query) use ($date) {
            $query->where('tanggal', $date->format('Y-m-d'));
        }])->where('hari', $dayName)->orderBy('waktu_mulai')->get();

        $exportData = collect();

        foreach ($schedules as $schedule) {
            if (!$schedule->kelas) continue;

            $students = Santri::where('kelas_id', $schedule->kelas->id)->orderBy('nama')->get();
            
            $mainAttendance = $schedule->absensi->first();
            
            $attendanceDetails = collect();
            if ($mainAttendance) {
                $attendanceDetails = AbsensiDetail::where('absensi_id', $mainAttendance->id)
                                        ->get()->keyBy('santri_id');
            }

            foreach ($students as $student) {
                $detail = $attendanceDetails->get($student->id);
                
                $status = $detail->status ?? 'Alpa';
                $jamHadir = ($detail && $status === 'Hadir' && $detail->jam_hadir) 
                            ? Carbon::parse($detail->jam_hadir)->format('H:i:s') 
                            : '-';
                
                $exportData->push([
                    'nisn'      => $student->nisn ?? '-',
                    'santri'    => $student->nama,
                    'kelas'     => $schedule->kelas->nama,
                    'mapel'     => $schedule->mata_pelajaran,
                    'waktu'     => Carbon::parse($schedule->waktu_mulai)->format('H:i') . ' - ' . Carbon::parse($schedule->waktu_selesai)->format('H:i'),
                    'status'    => $status,
                    'jam_hadir' => $jamHadir
                ]);
            }
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'NISN',
            'Nama Santri',
            'Kelas',
            'Mata Pelajaran',
            'Waktu',
            'Status Kehadiran',
            'Jam Hadir',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}