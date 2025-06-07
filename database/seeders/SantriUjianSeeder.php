<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\Ujian;
use App\Models\PSB\HasilUjian;
use App\Models\PSB\JawabanUjian;
use App\Models\PSB\Soal;
use Carbon\Carbon;

class SantriUjianSeeder extends Seeder
{
    public function run()
    {
        // Get the first santri from the seeder
        $santri = PendaftaranSantri::first();
        if (!$santri) {
            $this->command->error('No santri found. Please run SantriPPDBSeeder first.');
            return;
        }

        // Get all exams
        $ujians = Ujian::with('soals')->get();

        foreach ($ujians as $ujian) {
            // Create HasilUjian record
            $hasilUjian = HasilUjian::create([
                'santri_id' => $santri->id,
                'ujian_id' => $ujian->id,
                'waktu_mulai' => now(),
                'waktu_selesai' => now()->addMinutes($ujian->durasi),
                'status' => 'selesai',
                'nilai_akhir' => 0 // Will be updated after answers are created
            ]);

            $totalNilai = 0;
            $totalSoal = $ujian->soals->count();

            // Create answers for each question
            foreach ($ujian->soals as $soal) {
                $jawaban = '';
                $nilai = 0;

                if ($soal->tipe_soal === 'pg') {
                    // For multiple choice, randomly select an answer
                    $pilihanJawaban = json_decode($soal->pilihan_jawaban, true);
                    if (is_array($pilihanJawaban)) {
                        $jawaban = array_rand($pilihanJawaban);
                        // If answer matches correct answer, give full points
                        if ($jawaban == $soal->jawaban_benar) {
                            $nilai = $soal->bobot_nilai;
                        }
                    }
                } else {
                    // For essay, generate a sample answer
                    $jawaban = "Ini adalah contoh jawaban essay untuk soal nomor " . $soal->nomor_soal;
                    // For essay, give random points between 60-100% of max points
                    $nilai = round($soal->bobot_nilai * (rand(60, 100) / 100));
                }

                JawabanUjian::create([
                    'hasil_ujian_id' => $hasilUjian->id,
                    'soal_id' => $soal->id,
                    'jawaban' => $jawaban,
                    'nilai' => $nilai
                ]);

                $totalNilai += $nilai;
            }

            // Update final score
            $nilaiAkhir = $totalSoal > 0 ? round($totalNilai / $totalSoal, 2) : 0;
            $hasilUjian->update([
                'nilai_akhir' => $nilaiAkhir
            ]);
        }

        // Update santri status
        $santri->update([
            'status_santri' => 'sedang_ujian'
        ]);

        $this->command->info('Successfully created exam answers for santri: ' . $santri->nama_lengkap);
    }
} 