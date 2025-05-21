<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PsbPeriodesSeeder extends Seeder
{
    public function run()
    {
        DB::table('psb_periodes')->insert([
            'nama_jenjang' => 'SMP',
            'periode_mulai' => '2025-05-01',
            'periode_selesai' => '2025-06-30',
            'status_periode' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}