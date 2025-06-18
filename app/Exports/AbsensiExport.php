<?php

namespace App\Exports;

use App\Models\Absensi;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $tanggal;

    public function __construct($tanggal)
    {
        // Menyimpan tanggal yang dipilih dari dashboard
        $this->tanggal = $tanggal;
    }

    /**
     * Mengambil data absensi berdasarkan tanggal yang dipilih.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Mengambil semua detail absensi pada tanggal yang dipilih
        return Absensi::with(['details.santri', 'kelas', 'jadwalPelajaran'])
            ->where('tanggal', $this->tanggal)
            ->get()
            ->flatMap(function ($absensi) {
                // Mengubah struktur data agar setiap baris adalah satu santri
                return $absensi->details->map(function ($detail) use ($absensi) {
                    return [
                        'tanggal' => Carbon::parse($absensi->tanggal)->format('d-m-Y'),
                        'kelas' => $absensi->kelas->nama,
                        'mapel' => $absensi->jadwalPelajaran->mata_pelajaran,
                        'waktu' => Carbon::parse($absensi->jadwalPelajaran->waktu_mulai)->format('H:i') . ' - ' . Carbon::parse($absensi->jadwalPelajaran->waktu_selesai)->format('H:i'),
                        'nama_santri' => $detail->santri->nama,
                        'status' => $detail->status,
                        'jam_hadir' => $detail->jam_hadir ? Carbon::parse($detail->jam_hadir)->format('H:i:s') : '-',
                    ];
                });
            });
    }

    /**
     * Menentukan judul kolom (header) pada file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Tanggal',
            'Kelas',
            'Mata Pelajaran',
            'Waktu',
            'Nama Santri',
            'Status Kehadiran',
            'Jam Hadir',
        ];
    }

    /**
     * Memetakan data dari collection ke setiap baris di Excel.
     *
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        // Memastikan urutan data sesuai dengan headings
        return [
            $row['tanggal'],
            $row['kelas'],
            $row['mapel'],
            $row['waktu'],
            $row['nama_santri'],
            $row['status'],
            $row['jam_hadir'],
        ];
    }
}
