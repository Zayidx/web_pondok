<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\WaliSantri;
use App\Models\PSB\Periode;
use App\Models\PSB\Ujian;
use App\Models\PSB\Soal;
use App\Models\PSB\HasilUjian;
use App\Models\PSB\JawabanUjian;
use Carbon\Carbon;

class PpdbSantriSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // Create periode
        $periode = Periode::create([
            'nama_periode' => 'Penerimaan Santri 2025/2026',
            'periode_mulai' => $now->copy()->subMonth(),
            'periode_selesai' => $now->copy()->addMonth(),
            'status_periode' => 'active',
            'tahun_ajaran' => '2025/2026',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Create ujian
        $ujians = [
            [
                'nama_ujian' => 'Ujian Masuk Matematika',
                'mata_pelajaran' => 'Matematika',
                'periode_id' => $periode->id,
                'tanggal_ujian' => $now->copy()->subDays(5),
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '10:00:00',
                'status_ujian' => 'selesai',
                'created_at' => $now->copy()->subDays(7),
                'updated_at' => $now->copy()->subDays(5),
            ],
            [
                'nama_ujian' => 'Ujian Masuk Bahasa',
                'mata_pelajaran' => 'Bahasa Indonesia',
                'periode_id' => $periode->id,
                'tanggal_ujian' => $now->copy()->subDays(4),
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '10:00:00',
                'status_ujian' => 'selesai',
                'created_at' => $now->copy()->subDays(6),
                'updated_at' => $now->copy()->subDays(4),
            ],
            [
                'nama_ujian' => 'Ujian Masuk IPA',
                'mata_pelajaran' => 'IPA',
                'periode_id' => $periode->id,
                'tanggal_ujian' => $now->copy()->subDays(3),
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '10:00:00',
                'status_ujian' => 'selesai',
                'created_at' => $now->copy()->subDays(5),
                'updated_at' => $now->copy()->subDays(3),
            ],
            [
                'nama_ujian' => 'Ujian Masuk IPS',
                'mata_pelajaran' => 'IPS',
                'periode_id' => $periode->id,
                'tanggal_ujian' => $now->copy()->subDays(2),
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '10:00:00',
                'status_ujian' => 'selesai',
                'created_at' => $now->copy()->subDays(4),
                'updated_at' => $now->copy()->subDays(2),
            ],
            [
                'nama_ujian' => 'Ujian Masuk Agama',
                'mata_pelajaran' => 'Agama Islam',
                'periode_id' => $periode->id,
                'tanggal_ujian' => $now->copy()->subDays(1),
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '10:00:00',
                'status_ujian' => 'selesai',
                'created_at' => $now->copy()->subDays(3),
                'updated_at' => $now->copy()->subDays(1),
            ],
        ];

        foreach ($ujians as $ujianData) {
            Ujian::create($ujianData);
        }

        // Create soal
        $soals = [
            [
                'ujian_id' => 1,
                'pertanyaan' => '<p>Berapakah hasil dari 2 + 2?</p>',
                'tipe_soal' => 'pg',
                'opsi' => json_encode([
                    'A' => ['teks' => '3', 'bobot' => 0],
                    'B' => ['teks' => '4', 'bobot' => 10],
                    'C' => ['teks' => '5', 'bobot' => 0],
                    'D' => ['teks' => '6', 'bobot' => 0],
                ]),
                'kunci_jawaban' => 'B',
                'bobot_nilai' => 1,
                'poin' => 10,
                'created_at' => $now->copy()->subDays(7),
                'updated_at' => $now->copy()->subDays(7),
            ],
            [
                'ujian_id' => 1,
                'pertanyaan' => '<p>Jelaskan konsep dasar aljabar.</p>',
                'tipe_soal' => 'essay',
                'opsi' => null,
                'kunci_jawaban' => 'Penjelasan tentang variabel dan konstanta.',
                'bobot_nilai' => 1,
                'poin' => 20,
                'created_at' => $now->copy()->subDays(7),
                'updated_at' => $now->copy()->subDays(7),
            ],
            [
                'ujian_id' => 2,
                'pertanyaan' => '<p>Apa ibu kota Indonesia?</p>',
                'tipe_soal' => 'pg',
                'opsi' => json_encode([
                    'A' => ['teks' => 'Jakarta', 'bobot' => 10],
                    'B' => ['teks' => 'Bandung', 'bobot' => 0],
                    'C' => ['teks' => 'Surabaya', 'bobot' => 0],
                    'D' => ['teks' => 'Medan', 'bobot' => 0],
                ]),
                'kunci_jawaban' => 'A',
                'bobot_nilai' => 1,
                'poin' => 10,
                'created_at' => $now->copy()->subDays(6),
                'updated_at' => $now->copy()->subDays(6),
            ],
            [
                'ujian_id' => 3,
                'pertanyaan' => '<p>Apa rumus fotosintesis?</p>',
                'tipe_soal' => 'pg',
                'opsi' => json_encode([
                    'A' => ['teks' => '6CO2 + 6H2O -> C6H12O6 + 6O2', 'bobot' => 10],
                    'B' => ['teks' => 'CO2 + H2O -> C2H4O2', 'bobot' => 0],
                    'C' => ['teks' => 'H2O + O2 -> H2O2', 'bobot' => 0],
                    'D' => ['teks' => 'N2 + O2 -> NO2', 'bobot' => 0],
                ]),
                'kunci_jawaban' => 'A',
                'bobot_nilai' => 1,
                'poin' => 10,
                'created_at' => $now->copy()->subDays(5),
                'updated_at' => $now->copy()->subDays(5),
            ],
            [
                'ujian_id' => 4,
                'pertanyaan' => '<p>Siapa presiden pertama Indonesia?</p>',
                'tipe_soal' => 'pg',
                'opsi' => json_encode([
                    'A' => ['teks' => 'Soekarno', 'bobot' => 10],
                    'B' => ['teks' => 'Suharto', 'bobot' => 0],
                    'C' => ['teks' => 'Jokowi', 'bobot' => 0],
                    'D' => ['teks' => 'Megawati', 'bobot' => 0],
                ]),
                'kunci_jawaban' => 'A',
                'bobot_nilai' => 1,
                'poin' => 10,
                'created_at' => $now->copy()->subDays(4),
                'updated_at' => $now->copy()->subDays(4),
            ],
            [
                'ujian_id' => 5,
                'pertanyaan' => '<p>Apa arti Bismillah?</p>',
                'tipe_soal' => 'pg',
                'opsi' => json_encode([
                    'A' => ['teks' => 'Dengan nama Allah', 'bobot' => 10],
                    'B' => ['teks' => 'Segala puji bagi Allah', 'bobot' => 0],
                    'C' => ['teks' => 'Allah Maha Besar', 'bobot' => 0],
                    'D' => ['teks' => 'Tiada Tuhan selain Allah', 'bobot' => 0],
                ]),
                'kunci_jawaban' => 'A',
                'bobot_nilai' => 1,
                'poin' => 10,
                'created_at' => $now->copy()->subDays(3),
                'updated_at' => $now->copy()->subDays(3),
            ],
        ];

        foreach ($soals as $soalData) {
            Soal::create($soalData);
        }

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
                    'created_at' => $now->copy()->subDays(7),
                    'updated_at' => $now->copy()->subDays(1),
                ],
                'wali' => [
                    'nama_wali' => 'Budi Santoso',
                    'hubungan' => 'ayah',
                    'pekerjaan' => 'Wiraswasta',
                    'no_hp' => '081234567891',
                    'alamat' => 'Jl. Raya Jakarta No. 123, Jakarta Pusat',
                ],
                'hasil_ujian' => [
                    [
                        'ujian_id' => 1,
                        'jawaban_ujian' => [
                            ['soal_id' => 1, 'jawaban' => 'B', 'nilai' => 10], // PG: Benar
                            ['soal_id' => 2, 'jawaban' => 'Variabel adalah simbol yang mewakili angka.', 'nilai' => 15], // Essay
                        ],
                        'nilai_akhir' => 25,
                    ],
                    [
                        'ujian_id' => 2,
                        'jawaban_ujian' => [
                            ['soal_id' => 3, 'jawaban' => 'A', 'nilai' => 10], // PG: Benar
                        ],
                        'nilai_akhir' => 10,
                    ],
                    [
                        'ujian_id' => 3,
                        'jawaban_ujian' => [
                            ['soal_id' => 4, 'jawaban' => 'A', 'nilai' => 10], // PG: Benar
                        ],
                        'nilai_akhir' => 10,
                    ],
                    [
                        'ujian_id' => 4,
                        'jawaban_ujian' => [
                            ['soal_id' => 5, 'jawaban' => 'A', 'nilai' => 10], // PG: Benar
                        ],
                        'nilai_akhir' => 10,
                    ],
                    [
                        'ujian_id' => 5,
                        'jawaban_ujian' => [
                            ['soal_id' => 6, 'jawaban' => 'A', 'nilai' => 10], // PG: Benar
                        ],
                        'nilai_akhir' => 10,
                    ],
                ],
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
                    'created_at' => $now->copy()->subDays(8),
                    'updated_at' => $now->copy()->subDays(2),
                ],
                'wali' => [
                    'nama_wali' => 'Ahmad Hidayat',
                    'hubungan' => 'ayah',
                    'pekerjaan' => 'PNS',
                    'no_hp' => '081234567892',
                    'alamat' => 'Jl. Asia Afrika No. 45, Bandung',
                ],
                'hasil_ujian' => [
                    [
                        'ujian_id' => 1,
                        'jawaban_ujian' => [
                            ['soal_id' => 1, 'jawaban' => 'A', 'nilai' => 0], // PG: Salah
                            ['soal_id' => 2, 'jawaban' => 'Konstanta adalah nilai tetap.', 'nilai' => 10], // Essay
                        ],
                        'nilai_akhir' => 10,
                    ],
                ],
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
                    'created_at' => $now->copy()->subDays(9),
                    'updated_at' => $now->copy()->subDays(3),
                ],
                'wali' => [
                    'nama_wali' => 'Abdul Rahman',
                    'hubungan' => 'ayah',
                    'pekerjaan' => 'Dosen',
                    'no_hp' => '081234567893',
                    'alamat' => 'Jl. Pemuda No. 78, Surabaya',
                ],
                'hasil_ujian' => [
                    [
                        'ujian_id' => 1,
                        'jawaban_ujian' => [
                            ['soal_id' => 1, 'jawaban' => 'B', 'nilai' => 10], // PG: Benar
                            ['soal_id' => 2, 'jawaban' => 'Aljabar menggunakan simbol untuk menyelesaikan persamaan.', 'nilai' => 18], // Essay
                        ],
                        'nilai_akhir' => 28,
                    ],
                ],
            ],
        ];

        foreach ($santriData as $data) {
            // Create santri
            $santri = PendaftaranSantri::create($data['santri']);
            
            // Create wali santri
            $data['wali']['pendaftaran_santri_id'] = $santri->id;
            WaliSantri::create($data['wali']);

            // Create hasil ujian
            foreach ($data['hasil_ujian'] as $hasil) {
                $hasilUjian = HasilUjian::create([
                    'ujian_id' => $hasil['ujian_id'],
                    'santri_id' => $santri->id,
                    'status' => 'selesai',
                    'nilai_akhir' => $hasil['nilai_akhir'],
                    'waktu_mulai' => $now->copy()->subDays(5)->setTime(8, 0),
                    'waktu_selesai' => $now->copy()->subDays(5)->setTime(10, 0),
                    'created_at' => $now->copy()->subDays(5),
                    'updated_at' => $now->copy()->subDays(5),
                ]);

                // Create jawaban ujian
                foreach ($hasil['jawaban_ujian'] as $jawaban) {
                    JawabanUjian::create([
                        'hasil_ujian_id' => $hasilUjian->id,
                        'soal_id' => $jawaban['soal_id'],
                        'jawaban' => $jawaban['jawaban'],
                        'nilai' => $jawaban['nilai'],
                        'created_at' => $now->copy()->subDays(5),
                        'updated_at' => $now->copy()->subDays(5),
                    ]);
                }
            }
        }
    }
}