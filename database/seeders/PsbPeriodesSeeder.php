<?php

namespace Database\Seeders;

use App\Models\PSB\Periode;
use Illuminate\Database\Seeder;

/**
 * Seeder for populating the psb_periodes table.
 */
class PsbPeriodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Periode::create([
            'nama_periode' => 'Pendaftaran 2025',
            'periode_mulai' => '2025-01-01',
            'periode_selesai' => '2025-03-31',
            'status_periode' => 'active',
            'tahun_ajaran' => '2025/2026',
        ]);
    }
}