<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\WaliSantri;
use Carbon\Carbon;

class PpdbSantriSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $santriData = [
            [
                'santri' => [
                    'nama_lengkap' => 'Ahmad Santri',
                    'email' => 'ahmad.santri@example.com',
                    'nisn' => '1234567890',
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '2008-05-15',
                    'jenis_kelamin' => 'L',
                    'alamat' => 'Jl. Raya Jakarta No. 123',
                    'no_hp' => '081234567890',
                    'asal_sekolah' => 'SMP Negeri 1 Jakarta',
                    'tahun_lulus' => '2024',
                    'tipe_pendaftaran' => 'reguler',
                    'nama_ayah' => 'Budi Santoso',
                    'nama_ibu' => 'Siti Aminah',
                    'pekerjaan_ayah' => 'Wiraswasta',
                    'pekerjaan_ibu' => 'Guru',
                    'no_hp_ortu' => '081234567891',
                    'alamat_ortu' => 'Jl. Raya Jakarta No. 123, Jakarta Pusat',
                    'status' => 'ujian',
                    'status_santri' => 'sedang_ujian',
                    'tanggal_wawancara' => $now->copy()->subDays(2),
                    'mode' => 'offline',
                    'lokasi_offline' => 'Ruang Meeting Lt. 2',
                    'created_at' => $now->copy()->subDays(7), // Pendaftaran
                    'updated_at' => $now->copy()->subDays(1), // Update status ke ujian
                ],
                'wali' => [
                    'nama_wali' => 'Budi Santoso',
                    'hubungan' => 'ayah',
                    'pekerjaan' => 'Wiraswasta',
                    'no_hp' => '081234567891',
                    'alamat' => 'Jl. Raya Jakarta No. 123, Jakarta Pusat',
                ]
            ],
            [
                'santri' => [
                    'nama_lengkap' => 'Siti Santriwati',
                    'email' => 'siti.santriwati@example.com',
                    'nisn' => '1234567891',
                    'tempat_lahir' => 'Bandung',
                    'tanggal_lahir' => '2008-08-20',
                    'jenis_kelamin' => 'P',
                    'alamat' => 'Jl. Asia Afrika No. 45',
                    'no_hp' => '081234567891',
                    'asal_sekolah' => 'SMP Negeri 2 Bandung',
                    'tahun_lulus' => '2024',
                    'tipe_pendaftaran' => 'reguler',
                    'nama_ayah' => 'Ahmad Hidayat',
                    'nama_ibu' => 'Nur Hidayah',
                    'pekerjaan_ayah' => 'PNS',
                    'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                    'no_hp_ortu' => '081234567892',
                    'alamat_ortu' => 'Jl. Asia Afrika No. 45, Bandung',
                    'status' => 'ujian',
                    'status_santri' => 'sedang_ujian',
                    'tanggal_wawancara' => $now->copy()->subDays(3),
                    'mode' => 'online',
                    'link_online' => 'https://meet.google.com/example-meeting',
                    'created_at' => $now->copy()->subDays(8), // Pendaftaran
                    'updated_at' => $now->copy()->subDays(2), // Update status ke ujian
                ],
                'wali' => [
                    'nama_wali' => 'Ahmad Hidayat',
                    'hubungan' => 'ayah',
                    'pekerjaan' => 'PNS',
                    'no_hp' => '081234567892',
                    'alamat' => 'Jl. Asia Afrika No. 45, Bandung',
                ]
            ],
            [
                'santri' => [
                    'nama_lengkap' => 'Muhammad Santri',
                    'email' => 'muhammad.santri@example.com',
                    'nisn' => '1234567892',
                    'tempat_lahir' => 'Surabaya',
                    'tanggal_lahir' => '2008-10-10',
                    'jenis_kelamin' => 'L',
                    'alamat' => 'Jl. Pemuda No. 78',
                    'no_hp' => '081234567892',
                    'asal_sekolah' => 'SMP Negeri 1 Surabaya',
                    'tahun_lulus' => '2024',
                    'tipe_pendaftaran' => 'reguler',
                    'nama_ayah' => 'Abdul Rahman',
                    'nama_ibu' => 'Fatimah',
                    'pekerjaan_ayah' => 'Dosen',
                    'pekerjaan_ibu' => 'Dokter',
                    'no_hp_ortu' => '081234567893',
                    'alamat_ortu' => 'Jl. Pemuda No. 78, Surabaya',
                    'status' => 'ujian',
                    'status_santri' => 'sedang_ujian',
                    'tanggal_wawancara' => $now->copy()->subDays(4),
                    'mode' => 'offline',
                    'lokasi_offline' => 'Ruang Rapat Utama',
                    'created_at' => $now->copy()->subDays(9), // Pendaftaran
                    'updated_at' => $now->copy()->subDays(3), // Update status ke ujian
                ],
                'wali' => [
                    'nama_wali' => 'Abdul Rahman',
                    'hubungan' => 'ayah',
                    'pekerjaan' => 'Dosen',
                    'no_hp' => '081234567893',
                    'alamat' => 'Jl. Pemuda No. 78, Surabaya',
                ]
            ],
        ];

        foreach ($santriData as $data) {
            // Create santri
            $santri = PendaftaranSantri::create($data['santri']);
            
            // Create wali santri
            $data['wali']['pendaftaran_santri_id'] = $santri->id;
            WaliSantri::create($data['wali']);
        }
    }
} 