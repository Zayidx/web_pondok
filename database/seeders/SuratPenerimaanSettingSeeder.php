<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PSB\SuratPenerimaanSetting;

class SuratPenerimaanSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SuratPenerimaanSetting::create([
            'nama_pesantren' => 'Pondok Pesantren Al-Hikmah',
            'nama_yayasan' => 'Yayasan Pendidikan Islam Terpadu',
            'alamat_pesantren' => 'Jl. Pendidikan No. 123, Jakarta Selatan 12345',
            'telepon_pesantren' => '(021) 1234-5678',
            'email_pesantren' => 'info@alhikmah.ac.id',
            'nama_direktur' => 'Dr. H. Abdul Rahman, M.Pd',
            'nip_direktur' => '1234567890123456',
            'nama_kepala_admin' => 'Hj. Fatimah, S.Pd',
            'nip_kepala_admin' => '9876543210987654',
            'catatan_penting' => 'Orientasi santri baru dimulai tanggal 15 Juli 2024. Harap membawa Surat ini saat registrasi ulang. Pembayaran SPP semester pertama paling lambat 20 Juli 2024. Untuk informasi lebih lanjut hubungi bagian administrasi.',
            'tahun_ajaran' => '2025/2026',
            'tanggal_orientasi' => '2024-07-15',
            'batas_pembayaran_spp' => '2024-07-20'
        ]);
    }
} 