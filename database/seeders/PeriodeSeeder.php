<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PSB\Periode;
use Carbon\Carbon;

class PeriodeSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        
        $periods = [
            [
                'nama_periode' => 'Pendaftaran Santri Baru T.A. 2025/2026',
                'periode_mulai' => $now->copy()->subMonth(),
                'periode_selesai' => $now->copy()->addMonths(2),
                'status_periode' => 'active',
                'tahun_ajaran' => '2025/2026',
                'tipe_periode' => 'pendaftaran_baru',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_periode' => 'Pendaftaran Ulang Santri T.A. 2025/2026',
                'periode_mulai' => $now->copy()->addMonths(3),
                'periode_selesai' => $now->copy()->addMonths(4),
                'status_periode' => 'inactive',
                'tahun_ajaran' => '2025/2026',
                'tipe_periode' => 'daftar_ulang',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_periode' => 'Ujian Masuk Santri Baru T.A. 2025/2026',
                'periode_mulai' => $now->copy()->addMonths(2),
                'periode_selesai' => $now->copy()->addMonths(3),
                'status_periode' => 'inactive',
                'tahun_ajaran' => '2025/2026',
                'tipe_periode' => 'ujian_masuk',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_periode' => 'Wawancara Santri Baru T.A. 2025/2026',
                'periode_mulai' => $now->copy()->addMonths(2)->addDays(15),
                'periode_selesai' => $now->copy()->addMonths(3)->addDays(15),
                'status_periode' => 'inactive',
                'tahun_ajaran' => '2025/2026',
                'tipe_periode' => 'wawancara',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($periods as $period) {
            Periode::create($period);
        }
    }
} 