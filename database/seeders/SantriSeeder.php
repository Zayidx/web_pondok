<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
class SantriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $data = [
            [
                
                'foto'                  => '',
                'nama'                  => 'asep subardjo',
                'nisn'                  => '0102100612',
                'nism'                  => '131232150027240102',
                'kewarganegaraan'       => 'WNI',
                'email'                 => $faker->unique()->safeEmail,
                'nik'                   => '3215125401100001',
                'tempat_lahir'          => 'Karawang',
                'tanggal_lahir'         => '2010-01-14',
                'password'              => Hash::make('0102100612'),
                'jenis_kelamin'         => 'putera',
                'jumlah_saudara_kandung' => '1',
                'anak_ke'               => '1',
                'agama'                 => 'islam',
                'hobi'                  => 'membaca',
                'aktivitas_pendidikan'  => 'aktif',
                'npsn'                  => '70047049',
                'no_kip'                => 'MA Daarul Huffazh',
                'no_kk'                 => '3215122808070100',
                'nama_kepala_keluarga'   => 'MAMA',
                'kelas_id'              => 1,
                'kamar_id'              => 1,
                'asal_sekolah'          => "SMKN 1 Bekasi",
                'riwayat_penyakit'      => 'sehat',
                'status_kesantrian'     => 'aktif',
                'status_santri'         => 'reguler',
            ],
            [
                'foto'                  => '',
                'nama'                  => 'Muhammad Rajo',
                'nisn'                  => '0102100611',
                'nism'                  => '131232150027240102',
                'email'                 => $faker->unique()->safeEmail,
                'kewarganegaraan'       => 'WNI',
                'nik'                   => '3215125401100001',
                'tempat_lahir'          => 'Karawang',
                'tanggal_lahir'         => '2010-01-14',
                'password'              =>  Hash::make('0102100611'),
                'jenis_kelamin'         => 'putera',
                'jumlah_saudara_kandung' => '1',
                'anak_ke'               => '1',
                'agama'                 => 'islam',
                'hobi'                  => 'membaca',
                'aktivitas_pendidikan'  => 'aktif',
                'npsn'                  => '70047049',
                'no_kip'                => 'MA Daarul Huffazh',
                'no_kk'                 => '3215122808070100',
                'nama_kepala_keluarga'   => 'MAMA',
                'kelas_id'              => 1,
                'kamar_id'              => 1,
                'asal_sekolah'          => "MAN 14 Bandung",
                'riwayat_penyakit'      => 'sehat',
                'status_kesantrian'     => 'aktif',
                'status_santri'         => 'reguler',
            ],
            [
                'foto'                  => '',
                'nama'                  => 'Cipa',
                'nisn'                  => '023615',
                'nism'                  => '131232150027240102',
                'email'                 => $faker->unique()->safeEmail,
                'kewarganegaraan'       => 'WNI',
                'nik'                   => '3215125401100002',
                'tempat_lahir'          => 'Karawang',
                'tanggal_lahir'         => '2010-01-14',
                'password'              =>  Hash::make('023615'),
                'jenis_kelamin'         => 'putera',
                'jumlah_saudara_kandung' => '1',
                'anak_ke'               => '1',
                'agama'                 => 'islam',
                'hobi'                  => 'membaca',
                'aktivitas_pendidikan'  => 'aktif',
                'npsn'                  => '70047049',
                'no_kip'                => 'MA Daarul Huffazh',
                'no_kk'                 => '3215122808070100',
                'nama_kepala_keluarga'   => 'MAMA',
                'kelas_id'              => 1,
                'kamar_id'              => 1,
                'asal_sekolah'          => "MAN 14 Bandung",
                'riwayat_penyakit'      => 'sehat',
                'status_kesantrian'     => 'aktif',
                'status_santri'         => 'reguler',
            ],
        ];

        DB::table('santris')->insert($data);
    }
}
