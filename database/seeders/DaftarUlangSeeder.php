<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DaftarUlangSeeder extends Seeder
{
    public function run()
    {
        // Masukkan pengaturan rekening (sesuai 'psb_rekening_settings' di migration)
        DB::table('psb_rekening_settings')->insert([
            'nama_bank' => 'Bank Mandiri',  // Sesuaikan nama kolom jika berbeda
            'nomor_rekening' => '1234567890',
            'atas_nama' => 'Pondok Pesantren',
            'is_active' => true, // Tambahkan kolom ini jika ada di migration
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Masukkan rincian biaya (sesuai 'psb_rincian_biaya' di migration)
        DB::table('psb_rincian_biaya')->insert([
            [
                'nama_biaya' => 'Uang Pangkal',
                'jumlah' => 5000000, // Diubah dari 'nominal' menjadi 'jumlah'
                'is_active' => true,
                'keterangan' => 'Biaya uang pangkal masuk',
                'tahun_ajaran' => '2025/2026', // Tambahkan kolom ini jika ada di migration
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_biaya' => 'Seragam',
                'jumlah' => 1000000, // Diubah dari 'nominal' menjadi 'jumlah'
                'is_active' => true,
                'keterangan' => 'Biaya seragam lengkap',
                'tahun_ajaran' => '2025/2026', // Tambahkan kolom ini jika ada di migration
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_biaya' => 'SPP Bulan Pertama',
                'jumlah' => 500000, // Diubah dari 'nominal' menjadi 'jumlah'
                'is_active' => true,
                'keterangan' => 'SPP untuk bulan pertama',
                'tahun_ajaran' => '2025/2026', // Tambahkan kolom ini jika ada di migration
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Masukkan periode daftar ulang (sesuai 'psb_periode_daftar_ulang' di migration)
        DB::table('psb_periode_daftar_ulang')->insert([
            'nama_periode' => 'Daftar Ulang Gelombang 1', // Tambahkan kolom ini jika ada di migration
            'tanggal_mulai' => Carbon::now(), // Diubah dari 'periode_mulai'
            'tanggal_selesai' => Carbon::now()->addDays(14), // Diubah dari 'periode_selesai'
            'tahun_ajaran' => '2025/2026', // Tambahkan kolom ini jika ada di migration
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
