<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PSB\Ujian;
use App\Models\PSB\Soal;
use App\Models\PSB\Periode;
use Carbon\Carbon;

class UjianSeeder extends Seeder
{
    public function run(): void
    {
        // Create a periode if none exists
        $periode = Periode::firstOrCreate([
            'nama_periode' => 'Periode 2024/2025',
            'periode_mulai' => '2024-07-01',
            'periode_selesai' => '2025-06-30',
            'status_periode' => 'active',
            'tahun_ajaran' => '2024/2025',
        ]);

        $mataPelajaran = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'IPA',
            'Agama Islam'
        ];

        foreach ($mataPelajaran as $index => $mapel) {
            $ujian = Ujian::create([
                'nama_ujian' => "Ujian $mapel",
                'mata_pelajaran' => $mapel,
                'periode_id' => $periode->id,
                'tanggal_ujian' => Carbon::now()->addDays($index),
                'waktu_mulai' => '08:00',
                'waktu_selesai' => '10:00',
                'status_ujian' => 'aktif'
            ]);

            // Create 5 multiple choice questions
            for ($i = 1; $i <= 5; $i++) {
                $correctAnswer = rand(0, 3); // Random correct answer (0-3)
                $options = [
                    'A' => ['teks' => "Pilihan A untuk soal $i", 'bobot' => $correctAnswer === 0 ? 20 : 0],
                    'B' => ['teks' => "Pilihan B untuk soal $i", 'bobot' => $correctAnswer === 1 ? 20 : 0],
                    'C' => ['teks' => "Pilihan C untuk soal $i", 'bobot' => $correctAnswer === 2 ? 20 : 0],
                    'D' => ['teks' => "Pilihan D untuk soal $i", 'bobot' => $correctAnswer === 3 ? 20 : 0],
                ];

                Soal::create([
                    'ujian_id' => $ujian->id,
                    'pertanyaan' => "Pertanyaan PG $i untuk ujian $mapel?",
                    'tipe_soal' => 'pg',
                    'opsi' => $options,
                    'kunci_jawaban' => $correctAnswer,
                    'poin' => 20
                ]);
            }

            // Create 5 essay questions
            for ($i = 1; $i <= 5; $i++) {
                Soal::create([
                    'ujian_id' => $ujian->id,
                    'pertanyaan' => "Pertanyaan Essay $i untuk ujian $mapel?",
                    'tipe_soal' => 'essay',
                    'opsi' => null,
                    'kunci_jawaban' => null,
                    'poin' => 40
                ]);
            }
        }
    }
} 