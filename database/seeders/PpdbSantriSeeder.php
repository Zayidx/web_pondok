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
        $santriData = [
            [
                'santri' => [
                    'nama_lengkap' => 'Ahmad Santri',
                    'email' => 'ahmad.santri@example.com',
                    'nama_jenjang' => 'SMP',
                    'nisn' => '1234567890',
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '2008-05-15',
                    'jenis_kelamin' => 'L',
                    'agama' => 'Islam',
                    'no_whatsapp' => '081234567890',
                    'asal_sekolah' => 'SMP Negeri 1 Jakarta',
                    'tahun_lulus' => '2024',
                    'status_santri' => 'sedang_ujian',
                    'status_kesantrian' => 'aktif',
                    'tipe_pendaftaran' => 'reguler',
                    'periode_id' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                'wali' => [
                    'nama_ayah' => 'Budi Santoso',
                    'pekerjaan_ayah' => 'Wiraswasta',
                    'pendidikan_ayah' => 'S1',
                    'penghasilan_ayah' => '5-10 juta',
                    'nama_ibu' => 'Siti Aminah',
                    'pekerjaan_ibu' => 'Guru',
                    'pendidikan_ibu' => 'S1',
                    'no_telp_ibu' => '081234567891',
                    'alamat' => 'Jl. Raya Jakarta No. 123, Jakarta Pusat',
                ]
            ],
            [
                'santri' => [
                    'nama_lengkap' => 'Siti Santriwati',
                    'email' => 'siti.santriwati@example.com',
                    'nama_jenjang' => 'SMP',
                    'nisn' => '1234567891',
                    'tempat_lahir' => 'Bandung',
                    'tanggal_lahir' => '2008-08-20',
                    'jenis_kelamin' => 'P',
                    'agama' => 'Islam',
                    'no_whatsapp' => '081234567891',
                    'asal_sekolah' => 'SMP Negeri 2 Bandung',
                    'tahun_lulus' => '2024',
                    'status_santri' => 'sedang_ujian',
                    'status_kesantrian' => 'aktif',
                    'tipe_pendaftaran' => 'reguler',
                    'periode_id' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                'wali' => [
                    'nama_ayah' => 'Ahmad Hidayat',
                    'pekerjaan_ayah' => 'PNS',
                    'pendidikan_ayah' => 'S2',
                    'penghasilan_ayah' => '5-10 juta',
                    'nama_ibu' => 'Nur Hidayah',
                    'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                    'pendidikan_ibu' => 'S1',
                    'no_telp_ibu' => '081234567892',
                    'alamat' => 'Jl. Asia Afrika No. 45, Bandung',
                ]
            ],
            [
                'santri' => [
                    'nama_lengkap' => 'Muhammad Santri',
                    'email' => 'muhammad.santri@example.com',
                    'nama_jenjang' => 'SMP',
                    'nisn' => '1234567892',
                    'tempat_lahir' => 'Surabaya',
                    'tanggal_lahir' => '2008-10-10',
                    'jenis_kelamin' => 'L',
                    'agama' => 'Islam',
                    'no_whatsapp' => '081234567892',
                    'asal_sekolah' => 'SMP Negeri 1 Surabaya',
                    'tahun_lulus' => '2024',
                    'status_santri' => 'sedang_ujian',
                    'status_kesantrian' => 'aktif',
                    'tipe_pendaftaran' => 'reguler',
                    'periode_id' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                'wali' => [
                    'nama_ayah' => 'Abdul Rahman',
                    'pekerjaan_ayah' => 'Dosen',
                    'pendidikan_ayah' => 'S3',
                    'penghasilan_ayah' => '> 10 juta',
                    'nama_ibu' => 'Fatimah',
                    'pekerjaan_ibu' => 'Dokter',
                    'pendidikan_ibu' => 'S2',
                    'no_telp_ibu' => '081234567893',
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