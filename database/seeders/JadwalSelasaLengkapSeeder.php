<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ESantri\KategoriPelajaran;
use App\Models\ESantri\JadwalPelajaran;
use App\Models\Kelas;
use Illuminate\Support\Facades\Log;

class JadwalSelasaLengkapSeeder extends Seeder
{
    /**
     * Menjalankan proses seeding database.
     *
     * @return void
     */
    public function run()
    {
        $kategoriUmum = KategoriPelajaran::firstOrCreate(
            ['nama' => 'Pelajaran Umum'],
            [
                'deskripsi' => 'Mata pelajaran yang berkaitan dengan kurikulum nasional.',
                'role_guru' => 'umum',
            ]
        );

        $kategoriDiniyyah = KategoriPelajaran::firstOrCreate(
            ['nama' => 'Pelajaran Diniyyah'],
            [
                'deskripsi' => 'Mata pelajaran yang berkaitan dengan kurikulum nasional.',
                'role_guru' => 'umum', 
            ]
        );

        $kelasXA = Kelas::with('jenjang')->where('nama', 'Kelas X A')->first();
        $kelasXB = Kelas::with('jenjang')->where('nama', 'Kelas X B')->first();
        $kelasXIA = Kelas::with('jenjang')->where('nama', 'Kelas XI A')->first();

        $jadwals = [];

        if ($kelasXA && $kelasXA->jenjang_id) {
            $jadwals[] = ['kelas_id' => $kelasXA->id, 'jenjang_id' => $kelasXA->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Matematika Wajib', 'waktu_mulai' => '07:00:00', 'waktu_selesai' => '08:30:00'];
            $jadwals[] = ['kelas_id' => $kelasXA->id, 'jenjang_id' => $kelasXA->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Bahasa Indonesia', 'waktu_mulai' => '08:30:00', 'waktu_selesai' => '10:00:00'];
            $jadwals[] = ['kelas_id' => $kelasXA->id, 'jenjang_id' => $kelasXA->jenjang_id, 'kategori_pelajaran_id' => $kategoriDiniyyah->id, 'mata_pelajaran' => 'Fiqih', 'waktu_mulai' => '10:30:00', 'waktu_selesai' => '12:00:00'];
            $jadwals[] = ['kelas_id' => $kelasXA->id, 'jenjang_id' => $kelasXA->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Kimia', 'waktu_mulai' => '13:30:00', 'waktu_selesai' => '15:00:00'];
        } else {
            Log::warning("Seeder Peringatan: Kelas 'Kelas X A' atau relasi jenjangnya tidak ditemukan. Jadwal untuk kelas ini dilewati.");
        }

        if ($kelasXB && $kelasXB->jenjang_id) {
            $jadwals[] = ['kelas_id' => $kelasXB->id, 'jenjang_id' => $kelasXB->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Fisika', 'waktu_mulai' => '07:00:00', 'waktu_selesai' => '08:30:00'];
            $jadwals[] = ['kelas_id' => $kelasXB->id, 'jenjang_id' => $kelasXB->jenjang_id, 'kategori_pelajaran_id' => $kategoriDiniyyah->id, 'mata_pelajaran' => 'Aqidah Akhlak', 'waktu_mulai' => '08:30:00', 'waktu_selesai' => '10:00:00'];
            $jadwals[] = ['kelas_id' => $kelasXB->id, 'jenjang_id' => $kelasXB->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Biologi', 'waktu_mulai' => '10:30:00', 'waktu_selesai' => '12:00:00'];
            $jadwals[] = ['kelas_id' => $kelasXB->id, 'jenjang_id' => $kelasXB->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Bahasa Inggris', 'waktu_mulai' => '13:30:00', 'waktu_selesai' => '15:00:00'];
        } else {
            Log::warning("Seeder Peringatan: Kelas 'Kelas X B' atau relasi jenjangnya tidak ditemukan. Jadwal untuk kelas ini dilewati.");
        }

        if ($kelasXIA && $kelasXIA->jenjang_id) {
            $jadwals[] = ['kelas_id' => $kelasXIA->id, 'jenjang_id' => $kelasXIA->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Sejarah Indonesia', 'waktu_mulai' => '07:00:00', 'waktu_selesai' => '08:30:00'];
            $jadwals[] = ['kelas_id' => $kelasXIA->id, 'jenjang_id' => $kelasXIA->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Sosiologi', 'waktu_mulai' => '08:30:00', 'waktu_selesai' => '10:00:00'];
            $jadwals[] = ['kelas_id' => $kelasXIA->id, 'jenjang_id' => $kelasXIA->jenjang_id, 'kategori_pelajaran_id' => $kategoriDiniyyah->id, 'mata_pelajaran' => 'Ilmu Hadits', 'waktu_mulai' => '10:30:00', 'waktu_selesai' => '12:00:00'];
            $jadwals[] = ['kelas_id' => $kelasXIA->id, 'jenjang_id' => $kelasXIA->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Ekonomi', 'waktu_mulai' => '13:30:00', 'waktu_selesai' => '15:00:00'];
        } else {
            Log::warning("Seeder Peringatan: Kelas 'Kelas XI A' atau relasi jenjangnya tidak ditemukan. Jadwal untuk kelas ini dilewati.");
        }

        foreach ($jadwals as $jadwal) {
            JadwalPelajaran::updateOrCreate(
                [
                    'kelas_id' => $jadwal['kelas_id'],
                    'hari' => 'jumat',
                    'waktu_mulai' => $jadwal['waktu_mulai'],
                ],
                array_merge($jadwal, [
                    'hari' => 'jumat',
                    'role_guru' => 'umum'
                ])
            );
        }

        $this->command->info('Seeder untuk jadwal hari jumat (jadwal penuh) berhasil dijalankan.');
    }
}