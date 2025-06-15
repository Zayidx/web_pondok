<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PSB\SertifikatTemplate;

class SertifikatTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SertifikatTemplate::create([
            'nama_pesantren' => 'Pondok Pesantren Al-Hikmah',
            'nama_yayasan' => 'Yayasan Al-Hikmah',
            'alamat_pesantren' => 'Jl. Pesantren No. 123, Kota, Provinsi',
            'telepon_pesantren' => '(021) 1234567', // Diubah dari 'nomor_telepon'
            'email_pesantren' => 'info@alhikmah.ac.id',
            'catatan_penting' => json_encode([
                'Sertifikat ini adalah bukti resmi penerimaan santri baru',
                'Harap membawa dokumen asli saat daftar ulang',
                'Pembayaran daftar ulang dapat dilakukan melalui rekening yang telah ditentukan',
                'Informasi lebih lanjut dapat menghubungi panitia PPDB'
            ]),
            'nama_direktur' => 'KH. Ahmad Hidayat',
            'nip_direktur' => '198001012010011001',
            'nama_kepala_admin' => 'Ustadz Muhammad Rizki',
            'nip_kepala_admin' => '198501012010011002',
            // Tambahkan kolom-kolom baru jika ada di migration dan ingin diisi di seeder
            'tahun_ajaran' => '2025/2026',
            'tanggal_orientasi' => '2024-07-15',
            'batas_pembayaran_spp' => '2024-07-20'
        ]);
    }
}
