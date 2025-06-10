<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\WaliSantri;
use App\Models\PSB\Periode;
use App\Models\PSB\Ujian;
use App\Models\PSB\Soal;
use App\Models\PSB\HasilUjian;
use App\Models\PSB\JawabanUjian;
use Carbon\Carbon;

class PpdbSantriSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // Create periode
        $periode = Periode::create([
            'nama_periode' => 'Penerimaan Santri 2025/2026',
            'periode_mulai' => $now->copy()->subMonth(),
            'periode_selesai' => $now->copy()->addMonth(),
            'status_periode' => 'active',
            'tahun_ajaran' => '2025/2026'
        ]);

        // Create santri pendaftaran
        $santri1 = PendaftaranSantri::create([
                    'nama_lengkap' => 'Ahmad Santri',
                    'email' => 'ahmad.santri@example.com',
                    'nisn' => '1234567890',
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '2008-05-15',
                    'jenis_kelamin' => 'L',
                    'agama' => 'Islam',
                    'no_whatsapp' => '081234567890',
                    'asal_sekolah' => 'SMP Negeri 1 Jakarta',
                    'tahun_lulus' => '2024',
                    'tipe_pendaftaran' => 'reguler',
                    'status_santri' => 'sedang_ujian',
                    'tanggal_wawancara' => $now->copy()->addDays(5),
                    'jam_wawancara' => '08:00',
                    'mode' => 'offline',
                    'lokasi_offline' => 'Ruang Meeting Lt. 2',
                    'periode_id' => $periode->id,
                    'created_at' => $now->copy(),
                    'updated_at' => $now->copy()->addDays(6)
        ]);

        $santri2 = PendaftaranSantri::create([
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'nisn' => '0987654321',
                    'tempat_lahir' => 'Bandung',
                    'tanggal_lahir' => '2008-08-20',
                    'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'no_whatsapp' => '081234567892',
            'asal_sekolah' => 'SMP Islam Al-Fikri',
                    'tahun_lulus' => '2024',
                    'tipe_pendaftaran' => 'reguler',
            'status_santri' => 'menunggu',
            'periode_id' => $periode->id,
            'created_at' => $now->copy(),
            'updated_at' => $now->copy()
        ]);

        // Create ujian
        $ujian = Ujian::create([
            'nama_ujian' => 'Ujian Masuk PPDB 2025',
            'mata_pelajaran' => 'Matematika',
            'periode_id' => $periode->id,
            'tanggal_ujian' => $now->copy()->addDays(7),
            'waktu_mulai' => '08:00',
            'waktu_selesai' => '10:00',
            'status_ujian' => 'aktif'
        ]);

        // Create soal
        $soal1 = Soal::create([
            'ujian_id' => $ujian->id,
            'pertanyaan' => 'Berapakah hasil dari 5 x 7?',
            'tipe_soal' => 'pg',
            'opsi' => [
                ['text' => '25', 'bobot' => 0],
                ['text' => '35', 'bobot' => 1],
                ['text' => '40', 'bobot' => 0],
                ['text' => '45', 'bobot' => 0]
            ],
            'kunci_jawaban' => 1,
            'bobot_nilai' => 10
        ]);

        $soal2 = Soal::create([
            'ujian_id' => $ujian->id,
            'pertanyaan' => 'Jelaskan konsep perkalian!',
            'tipe_soal' => 'essay',
            'bobot_nilai' => 20
        ]);

            // Create hasil ujian
                $hasilUjian = HasilUjian::create([
            'ujian_id' => $ujian->id,
            'santri_id' => $santri1->id,
            'nilai_akhir' => 85.00,
                    'status' => 'selesai',
            'waktu_mulai' => $now->copy()->addDays(7)->setTime(8, 0),
            'waktu_selesai' => $now->copy()->addDays(7)->setTime(9, 30)
                ]);

                // Create jawaban ujian
        JawabanUjian::create([
            'hasil_ujian_id' => $hasilUjian->id,
            'soal_id' => $soal1->id,
            'jawaban' => '1',
            'nilai' => 10
        ]);

                    JawabanUjian::create([
                        'hasil_ujian_id' => $hasilUjian->id,
            'soal_id' => $soal2->id,
            'jawaban' => 'Perkalian adalah penjumlahan berulang dengan jumlah yang sama.',
            'nilai' => 18
        ]);
    }
}