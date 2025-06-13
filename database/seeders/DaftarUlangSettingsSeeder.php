<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DaftarUlangSettingsSeeder extends Seeder
{
    public function run()
    {
        // Insert default data untuk daftar_ulang_settings
        DB::table('daftar_ulang_settings')->insert([
            'bank' => 'BNI Syariah',
            'nomor_rekening' => '1234567890',
            'atas_nama' => 'Yayasan Pendidikan Islam',
            'catatan_transfer' => 'Harap mencantumkan nama santri dan kelas pada berita transfer',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
} 