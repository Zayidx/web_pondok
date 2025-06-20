<?php

namespace App\Exports;

use App\Models\ESantri\JadwalPelajaran;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class JadwalPiketExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $selectedDate;

    public function __construct($selectedDate)
    {
        $this->selectedDate = $selectedDate;
    }
    public function collection()
    {
        Carbon::setLocale('id');
        $selectedCarbonDate = Carbon::parse($this->selectedDate);
        $hariDipilih = $selectedCarbonDate->translatedFormat('l');

        $jadwalCollection = JadwalPelajaran::with('kelas')
            ->where('hari', $hariDipilih)
            ->get();
            
        $groupedJadwal = [];
        foreach ($jadwalCollection as $jadwal) {
            $kelasId = $jadwal->kelas_id;
            if (!$kelasId) continue;

            $namaKelas = $jadwal->kelas->nama ?? 'Tanpa Kelas';
            if (!isset($groupedJadwal[$kelasId])) {
                $groupedJadwal[$kelasId] = [
                    'kelas_nama'     => $namaKelas,
                    'jadwals'        => [],
                ];
            }
            $groupedJadwal[$kelasId]['jadwals'][] = $jadwal;
        }

        $exportData = collect();
        foreach ($groupedJadwal as $kelasId => $data) {
            $waktuMulai = $jadwalCollection->where('kelas_id', $kelasId)->min('waktu_mulai');
            $waktuSelesai = $jadwalCollection->where('kelas_id', $kelasId)->max('waktu_selesai');

            $exportData->push([
                'kelas_nama'   => $data['kelas_nama'],
                'total_mapel'  => count($data['jadwals']),
                'jadwal_masuk' => $waktuMulai ? Carbon::parse($waktuMulai)->format('H:i') : '-',
                'jadwal_pulang'=> $waktuSelesai ? Carbon::parse($waktuSelesai)->format('H:i') : '-',
            ]);
        }
        
        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Kelas',
            'Total Mapel',
            'Jadwal Masuk',
            'Jadwal Pulang',
        ];
    }
}