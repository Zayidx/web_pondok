<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PsbDokumenSeeder extends Seeder
{
    public function run()
    {
        $dokumen = [
            // Dokumen untuk Ahmad Santri (ID: 1)
            [
                'santri_id' => 1,
                'jenis_berkas' => 'Pas Foto',
                'nama_berkas' => 'foto_ahmad.jpg',
                'file_path' => 'dokumen/santri/1/foto_ahmad.jpg',
                'file_type' => 'image/jpeg',
                'file_size' => 500000, // 500KB
                'is_verified' => true,
                'keterangan' => 'Pas foto 3x4 latar merah',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'santri_id' => 1,
                'jenis_berkas' => 'Ijazah',
                'nama_berkas' => 'ijazah_ahmad.pdf',
                'file_path' => 'dokumen/santri/1/ijazah_ahmad.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 1000000, // 1MB
                'is_verified' => true,
                'keterangan' => 'Ijazah SD',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'santri_id' => 1,
                'jenis_berkas' => 'Kartu Keluarga',
                'nama_berkas' => 'kk_ahmad.pdf',
                'file_path' => 'dokumen/santri/1/kk_ahmad.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 800000, // 800KB
                'is_verified' => true,
                'keterangan' => 'Kartu Keluarga',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Dokumen untuk Budi Santoso (ID: 2)
            [
                'santri_id' => 2,
                'jenis_berkas' => 'Pas Foto',
                'nama_berkas' => 'foto_budi.jpg',
                'file_path' => 'dokumen/santri/2/foto_budi.jpg',
                'file_type' => 'image/jpeg',
                'file_size' => 450000, // 450KB
                'is_verified' => false,
                'keterangan' => 'Pas foto 3x4 latar biru',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'santri_id' => 2,
                'jenis_berkas' => 'Ijazah',
                'nama_berkas' => 'ijazah_budi.pdf',
                'file_path' => 'dokumen/santri/2/ijazah_budi.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 950000, // 950KB
                'is_verified' => false,
                'keterangan' => 'Ijazah SD',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'santri_id' => 2,
                'jenis_berkas' => 'Kartu Keluarga',
                'nama_berkas' => 'kk_budi.pdf',
                'file_path' => 'dokumen/santri/2/kk_budi.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 750000, // 750KB
                'is_verified' => false,
                'keterangan' => 'Kartu Keluarga',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('psb_dokumen')->insert($dokumen);
    }
} 
 
 
 
 
 