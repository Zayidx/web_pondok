<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PsbWaliSantriSeeder extends Seeder
{
    public function run()
    {
        $waliSantris = [
            [
                'pendaftaran_santri_id' => 1, // Ahmad Santri
                'nama_wali' => 'Budi',
                'hubungan' => 'ayah',
                'pekerjaan' => 'Wiraswasta',
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Contoh No. 123',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pendaftaran_santri_id' => 1, // Ahmad Santri
                'nama_wali' => 'Siti',
                'hubungan' => 'ibu',
                'pekerjaan' => 'Ibu Rumah Tangga',
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Contoh No. 123',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pendaftaran_santri_id' => 2, // Budi Santoso
                'nama_wali' => 'Joko',
                'hubungan' => 'ayah',
                'pekerjaan' => 'PNS',
                'no_hp' => '081234567893',
                'alamat' => 'Jl. Sample No. 456',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pendaftaran_santri_id' => 2, // Budi Santoso
                'nama_wali' => 'Ani',
                'hubungan' => 'ibu',
                'pekerjaan' => 'Guru',
                'no_hp' => '081234567893',
                'alamat' => 'Jl. Sample No. 456',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('psb_wali_santri')->insert($waliSantris);
    }
} 
 
 
 
 
 