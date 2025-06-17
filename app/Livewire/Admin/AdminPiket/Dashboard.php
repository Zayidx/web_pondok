<?php

namespace App\Livewire\Admin\AdminPiket;

use App\Exports\AbsensiExport;
use App\Models\ESantri\JadwalPelajaran;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Dashboard extends Component
{
    // Properti untuk menyimpan tanggal yang dipilih, defaultnya hari ini
    public $selectedDate;

    public function mount()
    {
        // Menginisialisasi tanggal dengan tanggal hari ini
        $this->selectedDate = now()->format('Y-m-d');
    }

    /**
     * Method untuk mengekspor data absensi ke file Excel.
     */
    public function exportExcel()
    {
        // Nama file yang akan diunduh
        $fileName = 'rekap-absensi-' . $this->selectedDate . '.xlsx';
        // Menggunakan pustaka Maatwebsite Excel untuk mengunduh file
        return Excel::download(new AbsensiExport($this->selectedDate), $fileName);
    }
    
    public function render()
    {
        Carbon::setLocale('id');

        // Mengambil nama hari dari tanggal yang dipilih
        $selectedCarbonDate = Carbon::parse($this->selectedDate);
        $hariDipilih = $selectedCarbonDate->translatedFormat('l');

        // Mengambil jadwal pelajaran berdasarkan hari dari tanggal yang dipilih
        $jadwalCollection = JadwalPelajaran::with(['kelas', 'kategoriPelajaran'])
            ->where('hari', $hariDipilih)
            ->get();

        $groupedJadwal = [];

        foreach ($jadwalCollection as $jadwal) {
            $kelasId = $jadwal->kelas_id;
            $namaKelas = $jadwal->kelas->nama ?? 'Tanpa Kelas';

            if (!isset($groupedJadwal[$kelasId])) {
                $groupedJadwal[$kelasId] = [
                    'kelas_id' => $kelasId,
                    'kelas_nama' => $namaKelas,
                    'jadwals' => [],
                    'waktu_mulai_terawal' => null,
                    'waktu_selesai_terakhir' => null,
                ];
            }

            $groupedJadwal[$kelasId]['jadwals'][] = $jadwal;

            $waktuMulai = Carbon::parse($jadwal->waktu_mulai);
            $waktuSelesai = Carbon::parse($jadwal->waktu_selesai);

            if ($groupedJadwal[$kelasId]['waktu_mulai_terawal'] === null || $waktuMulai->lt(Carbon::parse($groupedJadwal[$kelasId]['waktu_mulai_terawal']))) {
                $groupedJadwal[$kelasId]['waktu_mulai_terawal'] = $waktuMulai->toTimeString();
            }

            if ($groupedJadwal[$kelasId]['waktu_selesai_terakhir'] === null || $waktuSelesai->gt(Carbon::parse($groupedJadwal[$kelasId]['waktu_selesai_terakhir']))) {
                $groupedJadwal[$kelasId]['waktu_selesai_terakhir'] = $waktuSelesai->toTimeString();
            }
        }

        foreach ($groupedJadwal as $kelasId => $data) {
            $groupedJadwal[$kelasId]['jadwal_masuk'] = $data['waktu_mulai_terawal'] ? Carbon::parse($data['waktu_mulai_terawal'])->format('H:i') : '-';
            // FIX: Mengubah variabel $groupedJwal menjadi $groupedJadwal
            $groupedJadwal[$kelasId]['jadwal_pulang'] = $data['waktu_selesai_terakhir'] ? Carbon::parse($data['waktu_selesai_terakhir'])->format('H:i') : '-';
            $groupedJadwal[$kelasId]['total_mapel'] = count($data['jadwals']);
        }
        
        return view('livewire.admin.admin-piket.dashboard', [
            'tanggalDipilihFormatted' => $selectedCarbonDate->translatedFormat('d F Y'),
            'hariDipilih' => $hariDipilih,
            'groupedJadwal' => $groupedJadwal,
        ]);
    }
}
