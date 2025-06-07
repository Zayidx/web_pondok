<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HasilUjianSeeder extends Seeder
{
    public function run()
    {
        $ujian = DB::table('ujians')->first();
        $santri = DB::table('psb_pendaftaran_santri')->first();

        if (!$ujian || !$santri) {
            return;
        }

        $hasilUjian = DB::table('hasil_ujians')->insertGetId([
            'ujian_id' => $ujian->id,
            'santri_id' => $santri->id,
            'status' => 'selesai',
            'nilai_akhir' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $soals = DB::table('soals')->where('ujian_id', $ujian->id)->get();

        foreach ($soals as $soal) {
            $jawaban = $soal->tipe_soal === 'pg' ? ($soal->kunci_jawaban ?? 'A') : 'Ini adalah jawaban test untuk soal ini.';
            $nilai = $soal->tipe_soal === 'pg' && $jawaban === $soal->kunci_jawaban ? $soal->poin : ($soal->tipe_soal === 'essay' ? 0 : 0);
            DB::table('jawaban_ujians')->insert([
                'hasil_ujian_id' => $hasilUjian,
                'soal_id' => $soal->id,
                'jawaban' => $jawaban,
                'nilai' => $nilai,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}