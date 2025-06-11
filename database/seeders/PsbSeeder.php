<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PSB\Periode;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\WaliSantri; // TAMBAHKAN: Import model WaliSantri
use App\Models\Ujian;
use App\Models\Soal;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class PsbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $pendaftaranPeriode = Periode::where('tipe_periode', 'pendaftaran_baru')->firstOrFail();
        $ujianPeriode = Periode::where('tipe_periode', 'ujian_masuk')->firstOrFail();

        $this->command->info('Membuat 10 data santri lengkap beserta walinya...');
        for ($i = 0; $i < 10; $i++) {
            $gender = $faker->randomElement(['L', 'P']);
            $modeWawancara = $faker->randomElement(['online', 'offline']);
            $namaAyah = $faker->name('male');
            $pekerjaanAyah = $faker->jobTitle;
            $namaIbu = $faker->name('female');
            $pekerjaanIbu = $faker->jobTitle;

            // Langkah 1: Buat data santri
            $santri = PendaftaranSantri::create([
                'nama_jenjang'    => 'SMA',
                'nama_lengkap'    => $faker->name($gender == 'L' ? 'male' : 'female'),
                'alamat'          => $faker->address,
                'nisn'            => $faker->unique()->numerify('##########'),
                'tempat_lahir'    => $faker->city,
                'tanggal_lahir'   => $faker->dateTimeBetween('-17 years', '-15 years'),
                'jenis_kelamin'   => $gender,
                'agama'           => 'Islam',
                'no_whatsapp'     => '6281' . $faker->numerify('#########'),
                'email'           => $faker->unique()->safeEmail,
                'asal_sekolah'    => 'SMP Negeri ' . $faker->numberBetween(1, 20) . ' ' . $faker->city,
                'tahun_lulus'     => '2025',
                'tipe_pendaftaran' => $faker->randomElement(['reguler', 'olimpiade', 'internasional']),
                'status_santri'   => 'sedang_ujian',
                'password'        => Hash::make('password'),
                'periode_id'      => $pendaftaranPeriode->id,
                'nama_ayah'       => $namaAyah,
                'pekerjaan_ayah'  => $pekerjaanAyah,
                'nama_ibu'        => $namaIbu,
                'pekerjaan_ibu'   => $pekerjaanIbu,
                'no_telp_ibu'     => '085' . $faker->numerify('#########'),
                'tanggal_wawancara' => now()->addDays($i + 5)->format('Y-m-d'),
                'jam_wawancara'   => $faker->randomElement(['09:00:00', '10:00:00', '11:00:00', '14:00:00']),
                'mode'            => $modeWawancara,
                'link_online'     => ($modeWawancara == 'online') ? 'https://meet.google.com/' . $faker->lexify('???-????-???') : null,
                'lokasi_offline'  => ($modeWawancara == 'offline') ? 'Ruang Wawancara ' . $faker->randomElement(['A', 'B', 'C']) : null,
            ]);

            // =================================================================
            // **BAGIAN BARU: Membuat data WaliSantri untuk santri di atas**
            // =================================================================
            WaliSantri::create([
                'pendaftaran_santri_id' => $santri->id, // Tautkan ke santri yang baru dibuat
                'nama_wali'     => $namaAyah, // Gunakan nama ayah sebagai wali utama
                'hubungan'      => 'ayah',
                'pekerjaan'     => $pekerjaanAyah,
                'no_hp'         => $faker->phoneNumber,
                'alamat'        => $santri->alamat, // Gunakan alamat yang sama dengan santri
                // Mengisi kolom-kolom tambahan
                'nama_ayah'       => $namaAyah,
                'pekerjaan_ayah'  => $pekerjaanAyah,
                'pendidikan_ayah' => $faker->randomElement(['SMA Sederajat', 'D3', 'S1', 'S2']),
                'penghasilan_ayah'=> $faker->randomElement(['< Rp 2.000.000', 'Rp 2.000.000 - Rp 5.000.000', '> Rp 5.000.000']),
                'nama_ibu'        => $namaIbu,
                'pekerjaan_ibu'   => $pekerjaanIbu,
                'pendidikan_ibu'  => $faker->randomElement(['SMA Sederajat', 'D3', 'S1']),
                'no_telp_ibu'     => $santri->no_telp_ibu,
            ]);
        }
        $this->command->info('Data santri dan wali berhasil dibuat.');

        // Membuat 10 data ujian beserta soal-soalnya (tidak ada perubahan di sini)
        $this->command->info('Membuat 10 ujian beserta soal-soalnya...');
        $subjects = [
            'Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Ilmu Pengetahuan Alam', 
            'Fiqih', 'Aqidah Akhlak', 'Sejarah Kebudayaan Islam', 'Bahasa Arab', 'Tahfidz', 'Tes Potensi Akademik'
        ];
        foreach ($subjects as $subject) {
            $ujian = Ujian::create([/*...*/]);
            // =================================================================
            // **BAGIAN BARU: Membuat Soal untuk Ujian di atas**
            // =================================================================

            // Buat 3 Soal Pilihan Ganda
            for ($i = 1; $i <= 3; $i++) {
                $pilihan = ['A', 'B', 'C', 'D'];
                $kunciJawabanIndex = array_rand($pilihan);
                $opsiJawaban = [];

                foreach ($pilihan as $index => $huruf) {
                    $opsiJawaban[] = [
                        'teks' => "Ini adalah pilihan jawaban $huruf untuk soal no. $i.",
                        'bobot' => ($index == $kunciJawabanIndex) ? 100 : 0 // Bobot 100 untuk jawaban benar
                    ];
                }

                Soal::create([
                    'ujian_id'      => $ujian->id,
                    'pertanyaan'    => "Ini adalah pertanyaan Pilihan Ganda nomor $i untuk mata pelajaran $subject. Apa jawaban yang benar?",
                    'tipe_soal'     => 'pg',
                    'opsi'          => $opsiJawaban,
                    'kunci_jawaban' => $pilihan[$kunciJawabanIndex],
                    'poin'          => 5, // Poin untuk soal PG
                ]);
            }

            // Buat 2 Soal Essay
            for ($j = 1; $j <= 2; $j++) {
                Soal::create([
                    'ujian_id'      => $ujian->id,
                    'pertanyaan'    => "Ini adalah pertanyaan Essay nomor $j untuk mata pelajaran $subject. Jelaskan secara singkat.",
                    'tipe_soal'     => 'essay',
                    'poin'          => 10, // Poin untuk soal Essay
                ]);
            }
        }
        $this->command->info('Data ujian dan soal berhasil dibuat.');
    }
}