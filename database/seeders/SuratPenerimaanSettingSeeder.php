<?php
// File: database/seeders/SuratPenerimaanSettingSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PSB\SuratPenerimaanSetting;

class SuratPenerimaanSettingSeeder extends Seeder
{
    /**
     * Jalankan proses seeding database.
     */
    public function run(): void
    {
        // Mencari record dengan ID 1, jika ada akan di-update, jika tidak ada akan dibuat.
        // Ini memastikan hanya ada satu baris data pengaturan.
        SuratPenerimaanSetting::updateOrCreate(
            ['id' => 1], // Kunci untuk mencari record.
            [ // Data yang akan dimasukkan atau diperbarui.
                'nama_pesantren' => 'Pondok Pesantren Al-Hikmah',
                'nama_yayasan' => 'Yayasan Pendidikan Islam Terpadu',
                'alamat_pesantren' => 'Jl. Pendidikan No. 123, Jakarta Selatan 12345',
                'telepon_pesantren' => '(021) 1234-5678',
                'email_pesantren' => 'info@alhikmah.ac.id',
                // Logo dan stempel dikosongkan (null) karena akan diunggah dari halaman admin.
                'logo' => null,
                'stempel' => null,
                'nama_direktur' => 'Dr. H. Abdul Rahman, M.Pd',
                'nip_direktur' => '1234567890123456',
                'nama_kepala_admin' => 'Hj. Fatimah, S.Pd',
                'nip_kepala_admin' => '9876543210987654',
                'catatan_penting' => "Orientasi santri baru dimulai tanggal 15 Juli 2024.\nHarap membawa Surat ini saat registrasi ulang.\nPembayaran SPP semester pertama paling lambat 20 Juli 2024.\nUntuk informasi lebih lanjut hubungi bagian administrasi.",
                'tahun_ajaran' => '2025/2026',
                'tanggal_orientasi' => '2024-07-15',
                'batas_pembayaran_spp' => '2024-07-20'
            ]
        );
    }
}