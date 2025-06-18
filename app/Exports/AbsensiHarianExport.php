<?php

namespace App\Exports;

use App\Models\Absensi\AbsensiDetail;
use App\Models\ESantri\JadwalPelajaran;
use App\Models\Santri;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AbsensiHarianExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $selectedDate;

    // Komentar: Constructor untuk menerima tanggal yang dipilih dari komponen Dashboard.
    public function __construct($selectedDate)
    {
        $this->selectedDate = $selectedDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Komentar: Mengambil data tanggal dan nama hari sesuai input.
        $date = Carbon::parse($this->selectedDate);
        $dayName = $date->translatedFormat('l');

        // Komentar: Mengambil semua jadwal pelajaran pada hari yang dipilih.
        $schedules = JadwalPelajaran::with(['kelas', 'absensi' => function($query) use ($date) {
            $query->where('tanggal', $date->format('Y-m-d'));
        }])->where('hari', $dayName)->orderBy('waktu_mulai')->get();

        $exportData = collect();

        // Komentar: Melakukan loop untuk setiap jadwal pelajaran.
        foreach ($schedules as $schedule) {
            if (!$schedule->kelas) continue; // Lewati jika jadwal tidak memiliki kelas.

            // Komentar: Mengambil semua santri yang terdaftar di kelas tersebut.
            $students = Santri::where('kelas_id', $schedule->kelas->id)->orderBy('nama')->get();
            
            // Komentar: Mencari data absensi utama untuk jadwal dan tanggal ini.
            $mainAttendance = $schedule->absensi->first();
            
            $attendanceDetails = collect();
            if ($mainAttendance) {
                // Komentar: Jika ada data absensi, ambil semua detailnya.
                $attendanceDetails = AbsensiDetail::where('absensi_id', $mainAttendance->id)
                                        ->get()->keyBy('santri_id');
            }

            // Komentar: Melakukan loop untuk setiap santri untuk membuat baris data di Excel.
            foreach ($students as $student) {
                $detail = $attendanceDetails->get($student->id);
                
                // Komentar: Menentukan status dan jam hadir. Jika tidak ada detail, dianggap 'Alpa'.
                $status = $detail->status ?? 'Alpa';
                $jamHadir = ($detail && $status === 'Hadir' && $detail->jam_hadir) 
                            ? Carbon::parse($detail->jam_hadir)->format('H:i:s') 
                            : '-';
                
                // Komentar: Menambahkan satu baris data ke koleksi ekspor.
                $exportData->push([
                    'kelas'     => $schedule->kelas->nama,
                    'mapel'     => $schedule->mata_pelajaran,
                    'waktu'     => Carbon::parse($schedule->waktu_mulai)->format('H:i') . ' - ' . Carbon::parse($schedule->waktu_selesai)->format('H:i'),
                    'santri'    => $student->nama,
                    'status'    => $status,
                    'jam_hadir' => $jamHadir
                ]);
            }
        }

        return $exportData;
    }

    /**
     * @return array
     */
    // Komentar: Method untuk mendefinisikan header atau judul kolom di file Excel.
    public function headings(): array
    {
        return [
            'Kelas',
            'Mata Pelajaran',
            'Waktu',
            'Nama Santri',
            'Status Kehadiran',
            'Jam Hadir',
        ];
    }
}
