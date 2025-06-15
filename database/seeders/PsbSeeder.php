<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PSB\Periode;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\WaliSantri;
use App\Models\PSB\Ujian;
use App\Models\PSB\Soal;
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

        // Mengambil atau membuat periode yang relevan jika belum ada di database.
        // Periode ini digunakan untuk pendaftaran santri.
        $pendaftaranPeriode = Periode::firstOrCreate(
            ['tipe_periode' => 'pendaftaran_baru', 'tahun_ajaran' => '2025/2026'],
            ['nama_periode' => 'Pendaftaran Santri Baru 2025', 'tanggal_mulai' => now(), 'tanggal_selesai' => now()->addMonths(3)]
        );

        // Mengambil atau membuat periode untuk ujian masuk.
        $ujianPeriode = Periode::firstOrCreate(
            ['tipe_periode' => 'ujian_masuk', 'tahun_ajaran' => '2025/2026'],
            ['nama_periode' => 'Ujian Masuk Santri Baru 2025', 'tanggal_mulai' => now()->addMonths(3), 'tanggal_selesai' => now()->addMonths(4)]
        );

        // Daftar kemungkinan status santri
        // Diperbarui berdasarkan list status yang Anda berikan
        $santriStatuses = [
            'menunggu',          // Santri baru mendaftar, menunggu tindakan selanjutnya
            'wawancara',         // Santri sedang dalam tahap wawancara
            'sedang_ujian',      // Santri sedang/akan menjalani ujian
            'diterima',          // Santri telah diterima
            'ditolak',           // Pendaftaran santri ditolak
            'daftar_ulang'       // Santri sedang dalam proses daftar ulang
        ];

        // Daftar kemungkinan status pembayaran
        // $paymentStatuses = [
        //     null, // Belum ada bukti pembayaran
        //     'pending', // Bukti pembayaran sudah diunggah, menunggu verifikasi
        //     'verified', // Pembayaran sudah terverifikasi
        //     'rejected' // Pembayaran ditolak (misal: bukti tidak valid)
        // ];

        $this->command->info('Membuat 1000 data santri pendaftar lengkap beserta walinya...');
        for ($i = 0; $i < 100; $i++) {
            $gender = $faker->randomElement(['L', 'P']); // Jenis kelamin acak
            $modeWawancara = $faker->randomElement(['online', 'offline']); // Mode wawancara acak
            $namaAyah = $faker->name('male');
            $pekerjaanAyah = $faker->jobTitle;
            $namaIbu = $faker->name('female');
            $pekerjaanIbu = $faker->jobTitle;

            // Pilih status santri secara acak dari array yang telah didefinisikan
            $randomSantriStatus = $faker->randomElement($santriStatuses);
            // Pilih status pembayaran secara acak
            // $randomPaymentStatus = $faker->randomElement($paymentStatuses);

            // Tentukan apakah ada bukti pembayaran berdasarkan status pembayaran yang dipilih
            // $buktiPembayaran = null;
            // if ($randomPaymentStatus === 'pending' || $randomPaymentStatus === 'verified' || $randomPaymentStatus === 'rejected') {
            //     // Asumsi ada bukti pembayaran jika statusnya bukan null
            //     // Dalam skenario nyata, ini akan menjadi path file yang sebenarnya
            //     $buktiPembayaran = 'path/to/bukti_pembayaran_' . $faker->unique()->randomNumber(5) . '.jpg';
            // }

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
                'status_santri'   => $randomSantriStatus, // Menggunakan status acak di sini
                // 'status_pembayaran' => 'pending', // Menggunakan status pembayaran acak
                // 'bukti_pembayaran' => $buktiPembayaran, // Menambahkan bukti pembayaran jika status relevan
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

            // Langkah 2: Buat data wali santri yang terkait
            WaliSantri::create([
                'pendaftaran_santri_id' => $santri->id,
                'nama_wali'     => $namaAyah,
                'hubungan'      => 'ayah',
                'pekerjaan'     => $pekerjaanAyah,
                'no_hp'         => '6281' . $faker->numerify('#########'),
                'alamat'        => $santri->alamat,
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
        $this->command->info('Data santri pendaftar dan wali berhasil dibuat.');

        // Membuat data ujian beserta soal-soalnya
        $this->command->info('Membuat ujian beserta soal-soalnya...');
        $subjects = [
            'Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Ilmu Pengetahuan Alam',
            'Fiqih', 'Aqidah Akhlak', 'Sejarah Kebudayaan Islam', 'Bahasa Arab', 'Tahfidz', 'Tes Potensi Akademik'
        ];

        foreach ($subjects as $key => $subject) {
            $ujian = Ujian::create([
                'nama_ujian' => 'Ujian ' . $subject,
                'mata_pelajaran' => $subject, // Mengisi kolom mata_pelajaran yang wajib
                'tanggal_ujian' => now()->addDays($key + 1),
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '10:00:00',
                'periode_id' => $ujianPeriode->id,
            ]);

            // Buat 3 Soal Pilihan Ganda
            for ($i = 1; $i <= 3; $i++) {
                $pilihan = ['A', 'B', 'C', 'D'];
                $kunciJawabanIndex = array_rand($pilihan); // Pilih indeks kunci jawaban secara acak
                $opsiJawaban = [];

                foreach ($pilihan as $index => $huruf) {
                    $opsiJawaban[] = [
                        'teks' => "Ini adalah pilihan jawaban $huruf untuk soal no. $i.",
                        'bobot' => ($index == $kunciJawabanIndex) ? 100 : 0 // Bobot 100 untuk kunci jawaban, 0 untuk lainnya
                    ];
                }

                Soal::create([
                    'ujian_id'      => $ujian->id,
                    'pertanyaan'    => "Ini adalah pertanyaan Pilihan Ganda nomor $i untuk mata pelajaran $subject. Apa jawaban yang benar?",
                    'tipe_soal'     => 'pg',
                    'opsi'          => $opsiJawaban, // Menyimpan opsi jawaban sebagai JSON
                    'kunci_jawaban' => $kunciJawabanIndex,
                    'poin'          => 5,
                ]);
            }

            // Buat 2 Soal Essay
            for ($j = 1; $j <= 2; $j++) {
                Soal::create([
                    'ujian_id'      => $ujian->id,
                    'pertanyaan'    => "Ini adalah pertanyaan Essay nomor $j untuk mata pelajaran $subject. Jelaskan secara singkat.",
                    'tipe_soal'     => 'essay',
                    'poin'          => 10,
                ]);
            }
        }
        $this->command->info('Data ujian dan soal berhasil dibuat.');
    }
}
