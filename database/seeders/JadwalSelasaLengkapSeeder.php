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
        // 1. Membuat atau mencari Kategori Pelajaran.
        // Komentar: Ini memastikan kategori ada sebelum jadwal dibuat.
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
                // Komentar: Deskripsi disamakan dengan kategori umum sesuai permintaan.
                'deskripsi' => 'Mata pelajaran yang berkaitan dengan kurikulum nasional.',
                'role_guru' => 'umum', 
            ]
        );

        // 2. Mengambil data Kelas yang sudah ada (tidak membuat baru).
        // Komentar: Nama kelas disesuaikan dengan screenshot yang Anda berikan. Pastikan kelas ini sudah ada.
        $kelasXA = Kelas::with('jenjang')->where('nama', 'Kelas X A')->first();
        $kelasXB = Kelas::with('jenjang')->where('nama', 'Kelas X B')->first();
        $kelasXIA = Kelas::with('jenjang')->where('nama', 'Kelas XI A')->first();

        // Array untuk menyimpan data jadwal yang akan dibuat.
        $jadwals = [];

        // 3. Menyiapkan data jadwal penuh untuk hari Selasa.
        // Komentar: Setiap kelas sekarang memiliki jadwal dari pagi hingga sore.
        
        // Jadwal Penuh untuk Kelas X A
        if ($kelasXA && $kelasXA->jenjang_id) {
            $jadwals[] = ['kelas_id' => $kelasXA->id, 'jenjang_id' => $kelasXA->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Matematika Wajib', 'waktu_mulai' => '07:00:00', 'waktu_selesai' => '08:30:00'];
            $jadwals[] = ['kelas_id' => $kelasXA->id, 'jenjang_id' => $kelasXA->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Bahasa Indonesia', 'waktu_mulai' => '08:30:00', 'waktu_selesai' => '10:00:00'];
            // Istirahat Pagi (10:00 - 10:30)
            $jadwals[] = ['kelas_id' => $kelasXA->id, 'jenjang_id' => $kelasXA->jenjang_id, 'kategori_pelajaran_id' => $kategoriDiniyyah->id, 'mata_pelajaran' => 'Fiqih', 'waktu_mulai' => '10:30:00', 'waktu_selesai' => '12:00:00'];
            // Istirahat Siang & Sholat (12:00 - 13:30)
            $jadwals[] = ['kelas_id' => $kelasXA->id, 'jenjang_id' => $kelasXA->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Kimia', 'waktu_mulai' => '13:30:00', 'waktu_selesai' => '15:00:00'];
        } else {
            Log::warning("Seeder Peringatan: Kelas 'Kelas X A' atau relasi jenjangnya tidak ditemukan. Jadwal untuk kelas ini dilewati.");
        }

        // Jadwal Penuh untuk Kelas X B
        if ($kelasXB && $kelasXB->jenjang_id) {
            $jadwals[] = ['kelas_id' => $kelasXB->id, 'jenjang_id' => $kelasXB->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Fisika', 'waktu_mulai' => '07:00:00', 'waktu_selesai' => '08:30:00'];
            $jadwals[] = ['kelas_id' => $kelasXB->id, 'jenjang_id' => $kelasXB->jenjang_id, 'kategori_pelajaran_id' => $kategoriDiniyyah->id, 'mata_pelajaran' => 'Aqidah Akhlak', 'waktu_mulai' => '08:30:00', 'waktu_selesai' => '10:00:00'];
            // Istirahat Pagi (10:00 - 10:30)
            $jadwals[] = ['kelas_id' => $kelasXB->id, 'jenjang_id' => $kelasXB->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Biologi', 'waktu_mulai' => '10:30:00', 'waktu_selesai' => '12:00:00'];
            // Istirahat Siang & Sholat (12:00 - 13:30)
            $jadwals[] = ['kelas_id' => $kelasXB->id, 'jenjang_id' => $kelasXB->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Bahasa Inggris', 'waktu_mulai' => '13:30:00', 'waktu_selesai' => '15:00:00'];
        } else {
            Log::warning("Seeder Peringatan: Kelas 'Kelas X B' atau relasi jenjangnya tidak ditemukan. Jadwal untuk kelas ini dilewati.");
        }

        // Jadwal Penuh untuk Kelas XI A
        if ($kelasXIA && $kelasXIA->jenjang_id) {
            $jadwals[] = ['kelas_id' => $kelasXIA->id, 'jenjang_id' => $kelasXIA->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Sejarah Indonesia', 'waktu_mulai' => '07:00:00', 'waktu_selesai' => '08:30:00'];
            $jadwals[] = ['kelas_id' => $kelasXIA->id, 'jenjang_id' => $kelasXIA->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Sosiologi', 'waktu_mulai' => '08:30:00', 'waktu_selesai' => '10:00:00'];
            // Istirahat Pagi (10:00 - 10:30)
            $jadwals[] = ['kelas_id' => $kelasXIA->id, 'jenjang_id' => $kelasXIA->jenjang_id, 'kategori_pelajaran_id' => $kategoriDiniyyah->id, 'mata_pelajaran' => 'Ilmu Hadits', 'waktu_mulai' => '10:30:00', 'waktu_selesai' => '12:00:00'];
            // Istirahat Siang & Sholat (12:00 - 13:30)
            $jadwals[] = ['kelas_id' => $kelasXIA->id, 'jenjang_id' => $kelasXIA->jenjang_id, 'kategori_pelajaran_id' => $kategoriUmum->id, 'mata_pelajaran' => 'Ekonomi', 'waktu_mulai' => '13:30:00', 'waktu_selesai' => '15:00:00'];
        } else {
            Log::warning("Seeder Peringatan: Kelas 'Kelas XI A' atau relasi jenjangnya tidak ditemukan. Jadwal untuk kelas ini dilewati.");
        }

        // 4. Memasukkan semua data jadwal ke dalam database.
        // Komentar: Menggunakan updateOrCreate untuk menghindari duplikasi jika seeder dijalankan lebih dari sekali.
        foreach ($jadwals as $jadwal) {
            JadwalPelajaran::updateOrCreate(
                // Kunci untuk mencari jadwal yang sudah ada
                [
                    'kelas_id' => $jadwal['kelas_id'],
                    'hari' => 'selasa', // Mencari dengan huruf kecil
                    'waktu_mulai' => $jadwal['waktu_mulai'],
                ],
                // Data yang akan dibuat atau diupdate
                array_merge($jadwal, [
                    'hari' => 'selasa', // Membuat dengan huruf kecil agar sesuai dengan logika aplikasi
                    'role_guru' => 'umum'
                ])
            );
        }

        // Komentar: Memberi pesan output di console bahwa seeder berhasil dijalankan.
        $this->command->info('Seeder untuk jadwal hari Selasa (jadwal penuh) berhasil dijalankan.');
    }
}
