<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        // 1. Membuat tabel psb_periodes
        // Tabel ini menyimpan informasi tentang periode Penerimaan Santri Baru (PSB),
        // seperti nama periode, tanggal mulai dan selesai, status, tahun ajaran, dan tipe periode.
        Schema::create('psb_periodes', function (Blueprint $table) {
            $table->id(); // Kolom ID utama
            $table->string('nama_periode'); // Nama unik untuk periode PSB
            $table->date('periode_mulai'); // Tanggal mulai periode
            $table->date('periode_selesai'); // Tanggal selesai periode
            $table->enum('status_periode', ['active', 'inactive'])->default('inactive'); // Status periode: aktif atau tidak aktif
            $table->string('tahun_ajaran'); // Tahun ajaran terkait (misal: "2023/2024")
            $table->enum('tipe_periode', ['pendaftaran_baru', 'daftar_ulang', 'ujian_masuk', 'wawancara'])->default('pendaftaran_baru'); // Tipe periode
            $table->timestamps(); // Kolom created_at dan updated_at
        });

        // 2. Membuat tabel psb_pendaftaran_santri
        // Tabel ini menyimpan data lengkap pendaftaran santri, termasuk informasi pribadi,
        // asal sekolah, status pendaftaran, jadwal wawancara, dan data orang tua.
        Schema::create('psb_pendaftaran_santri', function (Blueprint $table) {
            $table->id(); // Kolom ID utama
            $table->string('nama_jenjang')->nullable(); // Jenjang pendidikan yang dipilih (misal: "SMP", "SMA")
            $table->string('nama_lengkap'); // Nama lengkap calon santri
            $table->string('alamat')->nullable(); // Alamat calon santri
            $table->string('nisn')->unique(); // Nomor Induk Siswa Nasional, harus unik
            $table->string('tempat_lahir'); // Tempat lahir calon santri
            $table->date('tanggal_lahir'); // Tanggal lahir calon santri
            $table->enum('jenis_kelamin', ['L', 'P']); // Jenis kelamin: Laki-laki atau Perempuan
            $table->string('agama'); // Agama calon santri
            $table->string('no_whatsapp')->nullable(); // Nomor WhatsApp yang bisa dihubungi
            $table->string('email')->unique(); // Alamat email, harus unik
            $table->string('asal_sekolah'); // Nama sekolah asal
            $table->string('tahun_lulus')->nullable(); // Tahun kelulusan dari sekolah asal
            $table->enum('tipe_pendaftaran', ['reguler', 'olimpiade', 'internasional'])->nullable(); // Tipe pendaftaran
            $table->enum('status_santri', ['menunggu', 'wawancara', 'sedang_ujian', 'diterima', 'ditolak', 'daftar_ulang'])->default('menunggu'); // Status pendaftaran santri
            $table->text('reason_rejected')->nullable(); // Alasan jika pendaftaran ditolak
            $table->date('tanggal_wawancara')->nullable(); // Tanggal wawancara yang dijadwalkan
            $table->time('jam_wawancara')->nullable(); // Jam wawancara yang dijadwalkan
            $table->enum('mode', ['online', 'offline'])->nullable(); // Mode wawancara: online atau offline
            $table->string('link_online', 255)->nullable(); // Link untuk wawancara online (jika mode online)
            $table->string('lokasi_offline', 255)->nullable(); // Lokasi untuk wawancara offline (jika mode offline)
            $table->string('status_kesantrian')->nullable(); // Status kesantrian (misal: "Santri Baru", "Santri Aktif")
            $table->string('no_hp')->nullable(); // Nomor HP calon santri
            $table->string('no_hp_ortu')->nullable(); // Nomor HP orang tua/wali
            $table->string('nama_ayah')->nullable(); // Nama ayah
            $table->string('nama_ibu')->nullable(); // Nama ibu
            $table->string('pekerjaan_ayah')->nullable(); // Pekerjaan ayah
            $table->string('pekerjaan_ibu')->nullable(); // Pekerjaan ibu
            $table->string('no_telp_ibu')->nullable(); // Nomor telepon ibu
            $table->string('alamat_ortu')->nullable(); // Alamat orang tua/wali
            $table->enum('status', ['daftar', 'verifikasi', 'ujian', 'wawancara', 'diterima', 'ditolak'])->default('daftar'); // Status pendaftaran keseluruhan
            $table->rememberToken(); // Kolom untuk "remember me" jika digunakan dalam otentikasi
            $table->string('password')->nullable(); // Password jika santri memiliki akun login
            $table->enum('status_pembayaran', ['pending', 'verified', 'rejected'])->nullable(); // Status pembayaran pendaftaran
            $table->date('tanggal_pembayaran')->nullable(); // Tanggal pembayaran pendaftaran
            $table->decimal('rata_rata_ujian', 5, 2)->nullable()->comment('Menyimpan nilai rata-rata dari semua ujian'); // Rata-rata nilai ujian
            $table->decimal('total_nilai_semua_ujian', 8, 2)->default(0)->comment('Menyimpan total nilai dari semua ujian yang telah diselesaikan'); // Total nilai dari semua ujian
            $table->foreignId('verified_by')->nullable()->constrained('users'); // ID user yang melakukan verifikasi
            $table->timestamp('verified_at')->nullable(); // Tanggal dan waktu verifikasi
            $table->text('catatan_verifikasi')->nullable(); // Catatan dari proses verifikasi
            $table->foreignId('periode_id')->constrained('psb_periodes')->onDelete('cascade'); // Foreign key ke tabel psb_periodes
            $table->timestamps(); // Kolom created_at dan updated_at
        });

        // 3. Membuat tabel psb_pembayaran
        // Tabel ini mencatat semua transaksi pembayaran yang dilakukan oleh santri,
        // termasuk nominal, detail pengirim, bukti, dan status verifikasi.
        Schema::create('psb_pembayaran', function (Blueprint $table) {
            $table->id(); // Kolom ID utama untuk setiap pembayaran
            $table->foreignId('santri_id')->constrained('psb_pendaftaran_santri')->onDelete('cascade'); // Foreign key ke santri yang melakukan pembayaran
            $table->decimal('nominal', 12, 2); // Nominal yang dibayarkan
            $table->date('tanggal_pembayaran'); // Tanggal saat santri melakukan transfer
            $table->string('bank_pengirim'); // Bank asal transfer
            $table->string('nama_pengirim'); // Nama pemilik rekening pengirim
            $table->string('bukti_pembayaran'); // Path atau lokasi file bukti pembayaran yang diunggah
            $table->enum('status_pembayaran', ['pending', 'verified', 'rejected'])->nullable(); // Status pembayaran
            $table->text('catatan_verifikasi')->nullable(); // Catatan verifikasi untuk pembayaran (ditambahkan dari migrasi kedua)
            $table->timestamps(); // Kolom created_at dan updated_at standar
        });

        // 4. Membuat tabel psb_wali_santri
        // Tabel ini menyimpan informasi terpisah tentang wali santri,
        // termasuk hubungan, pekerjaan, kontak, dan detail orang tua.
        Schema::create('psb_wali_santri', function (Blueprint $table) {
            $table->id(); // Kolom ID utama
            $table->foreignId('pendaftaran_santri_id')->constrained('psb_pendaftaran_santri')->onDelete('cascade'); // Foreign key ke tabel psb_pendaftaran_santri
            $table->string('nama_wali'); // Nama lengkap wali
            $table->enum('hubungan', ['ayah', 'ibu', 'wali'])->default('wali'); // Hubungan dengan santri
            $table->string('pekerjaan'); // Pekerjaan wali
            $table->string('no_hp'); // Nomor HP wali
            $table->string('alamat'); // Alamat wali
            // Kolom tambahan hasil alter dari migrasi pertama
            $table->string('nama_ayah')->nullable(); // Nama ayah (jika wali bukan ayah)
            $table->string('pekerjaan_ayah')->nullable(); // Pekerjaan ayah
            $table->string('pendidikan_ayah')->nullable(); // Pendidikan terakhir ayah
            $table->string('penghasilan_ayah')->nullable(); // Penghasilan ayah
            $table->string('nama_ibu')->nullable(); // Nama ibu (jika wali bukan ibu)
            $table->string('pekerjaan_ibu')->nullable(); // Pekerjaan ibu
            $table->string('pendidikan_ibu')->nullable(); // Pendidikan terakhir ibu
            $table->string('no_telp_ibu')->nullable(); // Nomor telepon ibu
            $table->timestamps(); // Kolom created_at dan updated_at
        });

        // 5. Membuat tabel psb_dokumen
        // Tabel ini untuk mengelola dokumen-dokumen yang diunggah oleh santri selama proses pendaftaran,
        // seperti akta lahir, kartu keluarga, dll.
        Schema::create('psb_dokumen', function (Blueprint $table) {
            $table->id(); // Kolom ID utama
            $table->foreignId('santri_id')->constrained('psb_pendaftaran_santri')->onDelete('cascade'); // Foreign key ke tabel psb_pendaftaran_santri
            $table->string('jenis_berkas'); // Jenis berkas (misal: "Akta Lahir", "Kartu Keluarga")
            $table->string('nama_berkas')->nullable(); // Nama berkas yang diunggah
            $table->string('file_path'); // Path penyimpanan file di server
            $table->string('file_type')->nullable(); // Tipe file (misal: "application/pdf", "image/jpeg")
            $table->integer('file_size')->nullable(); // Ukuran file dalam bytes
            $table->boolean('is_verified')->default(false); // Status verifikasi dokumen
            $table->text('keterangan')->nullable(); // Keterangan tambahan tentang dokumen
            $table->timestamps(); // Kolom created_at dan updated_at
        });

        // 6. Membuat tabel ujians
        // Tabel ini mendefinisikan detail setiap ujian yang akan dilaksanakan,
        // seperti nama ujian, mata pelajaran, periode, dan jadwal.
        Schema::create('ujians', function (Blueprint $table) {
            $table->id(); // Kolom ID utama
            $table->string('nama_ujian'); // Nama ujian (misal: "Ujian Tulis Umum", "Ujian Hafalan")
            $table->string('mata_pelajaran'); // Mata pelajaran ujian
            $table->foreignId('periode_id')->constrained('psb_periodes')->onDelete('cascade'); // Foreign key ke tabel psb_periodes
            $table->date('tanggal_ujian'); // Tanggal pelaksanaan ujian
            $table->time('waktu_mulai'); // Waktu mulai ujian
            $table->time('waktu_selesai'); // Waktu selesai ujian
            $table->enum('status_ujian', ['draft', 'aktif', 'selesai'])->default('draft'); // Status ujian
            $table->timestamps(); // Kolom created_at dan updated_at
        });

        // 7. Membuat tabel soals
        // Tabel ini berisi detail setiap soal ujian, termasuk pertanyaan, tipe soal,
        // opsi jawaban (untuk PG), kunci jawaban, dan poin.
        Schema::create('soals', function (Blueprint $table) {
            $table->id(); // Kolom ID utama
            $table->foreignId('ujian_id')->constrained('ujians')->onDelete('cascade'); // Foreign key ke tabel ujians
            $table->text('pertanyaan'); // Teks pertanyaan soal
            $table->enum('tipe_soal', ['pg', 'essay'])->default('pg'); // Tipe soal: pilihan ganda (pg) atau esai
            $table->json('opsi')->nullable()->comment('Format: [{"teks": "Teks opsi", "bobot": 100}, ...]'); // Opsi jawaban untuk soal pilihan ganda dalam format JSON
            $table->string('kunci_jawaban')->nullable()->comment('Untuk PG: A,B,C,D'); // Kunci jawaban untuk soal pilihan ganda
            $table->integer('poin')->default(100)->comment('Poin maksimal untuk soal essay'); // Poin maksimal untuk soal esai
            $table->timestamps(); // Kolom created_at dan updated_at
        });

        // 8. Membuat tabel hasil_ujians
        // Tabel ini menyimpan ringkasan hasil ujian untuk setiap santri,
        // termasuk nilai akhir dan status pengerjaan ujian.
        Schema::create('hasil_ujians', function (Blueprint $table) {
            $table->id(); // Kolom ID utama
            $table->foreignId('ujian_id')->constrained('ujians')->onDelete('cascade'); // Foreign key ke tabel ujians
            $table->foreignId('santri_id')->constrained('psb_pendaftaran_santri')->onDelete('cascade'); // Foreign key ke tabel psb_pendaftaran_santri
            $table->decimal('nilai_akhir', 5, 2)->default(0); // Nilai akhir yang diperoleh santri
            $table->enum('status', ['belum_mulai', 'sedang_mengerjakan', 'selesai'])->default('belum_mulai'); // Status pengerjaan ujian
            $table->dateTime('waktu_mulai')->nullable(); // Waktu santri mulai mengerjakan ujian
            $table->dateTime('waktu_selesai')->nullable(); // Waktu santri selesai mengerjakan ujian
            $table->timestamps(); // Kolom created_at dan updated_at
        });

        // 9. Membuat tabel jawaban_ujians
        // Tabel ini mencatat setiap jawaban santri untuk setiap soal dalam ujian,
        // serta nilai yang diberikan dan komentar (jika ada).
        Schema::create('jawaban_ujians', function (Blueprint $table) {
            $table->id(); // Kolom ID utama
            $table->foreignId('hasil_ujian_id')->constrained('hasil_ujians')->onDelete('cascade'); // Foreign key ke tabel hasil_ujians
            $table->foreignId('soal_id')->constrained('soals')->onDelete('cascade'); // Foreign key ke tabel soals
            $table->text('jawaban')->nullable(); // Jawaban yang diberikan santri
            $table->integer('nilai')->nullable(); // Nilai yang diperoleh untuk jawaban ini
            $table->text('komentar')->nullable(); // Komentar dari pengoreksi
            $table->timestamps(); // Kolom created_at dan updated_at
        });

        // 10. Membuat tabel wawancara_schedules
        // Tabel ini mengelola jadwal wawancara untuk santri yang mendaftar.
        Schema::create('wawancara_schedules', function (Blueprint $table) {
            $table->id(); // Kolom ID utama
            $table->foreignId('santri_id')->constrained('psb_pendaftaran_santri')->onDelete('cascade'); // Foreign key ke tabel psb_pendaftaran_santri
            $table->dateTime('jadwal_wawancara'); // Tanggal dan waktu wawancara yang dijadwalkan
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending'); // Status wawancara
            $table->text('catatan')->nullable(); // Catatan tambahan untuk wawancara
            $table->timestamps(); // Kolom created_at dan updated_at
        });

        // 11. Membuat tabel psb_rekening_settings
        // Tabel untuk pengaturan informasi rekening bank yang digunakan untuk pembayaran PSB.
        Schema::create('psb_rekening_settings', function (Blueprint $table) {
            $table->id(); // Kolom ID utama
            $table->string('nama_bank'); // Nama bank
            $table->string('nomor_rekening'); // Nomor rekening bank
            $table->string('atas_nama'); // Nama pemilik rekening
            $table->string('catatan_transfer'); // Catatan penting untuk transfer
            $table->boolean('is_active')->default(true); // Status keaktifan rekening
            $table->timestamps(); // Kolom created_at dan updated_at
        });

        // 12. Membuat tabel psb_rincian_biaya
        // Tabel ini mendefinisikan rincian biaya yang harus dibayar santri,
        // seperti biaya pendaftaran, SPP, dll.
        Schema::create('psb_rincian_biaya', function (Blueprint $table) {
            $table->id(); // Kolom ID utama
            $table->string('nama_biaya'); // Nama jenis biaya (misal: "Biaya Pendaftaran", "SPP")
            $table->decimal('jumlah', 12, 2); // Jumlah nominal biaya
            $table->string('tahun_ajaran'); // Tahun ajaran terkait biaya
            $table->text('keterangan')->nullable(); // Keterangan tambahan
            $table->boolean('is_active')->default(true); // Status keaktifan biaya
            $table->timestamps(); // Kolom created_at dan updated_at
        });

        // 13. Membuat tabel psb_periode_daftar_ulang
        // Tabel ini mengelola informasi periode untuk daftar ulang santri.
        Schema::create('psb_periode_daftar_ulang', function (Blueprint $table) {
            $table->id(); // Kolom ID utama
            $table->string('nama_periode'); // Nama periode daftar ulang
            $table->date('tanggal_mulai'); // Tanggal mulai periode daftar ulang
            $table->date('tanggal_selesai'); // Tanggal selesai periode daftar ulang
            $table->string('tahun_ajaran'); // Tahun ajaran terkait
            $table->boolean('is_active')->default(true); // Status keaktifan periode
            $table->timestamps(); // Kolom created_at dan updated_at
        });

        // 14. Membuat tabel psb_sertifikat_templates
        // Tabel ini menyimpan data template untuk sertifikat pendaftaran santri,
        // termasuk detail pesantren dan informasi penandatangan.
        Schema::create('psb_sertifikat_templates', function (Blueprint $table) {
            $table->id(); // Kolom ID utama
            $table->string('nama_pesantren'); // Nama pesantren
            $table->string('nama_yayasan'); // Nama yayasan yang menaungi pesantren
            $table->text('alamat_pesantren'); // Alamat lengkap pesantren
            $table->string('telepon_pesantren'); // Nomor telepon pesantren
            $table->string('email_pesantren'); // Alamat email pesantren
            $table->string('logo')->nullable(); // Path atau URL logo pesantren
            $table->string('ttd_direktur')->nullable(); // Path atau URL tanda tangan direktur
            $table->string('ttd_admin')->nullable(); // Path atau URL tanda tangan admin
            $table->text('catatan_penting'); // Catatan penting untuk sertifikat
            $table->string('nama_direktur'); // Nama direktur
            $table->string('nip_direktur'); // NIP direktur
            $table->string('nama_kepala_admin'); // Nama kepala admin
            $table->string('nip_kepala_admin'); // NIP kepala admin
            // Kolom baru yang diperlukan oleh seeder
            $table->string('tahun_ajaran')->nullable(); // Tahun ajaran terkait sertifikat
            $table->date('tanggal_orientasi')->nullable(); // Tanggal orientasi santri baru
            $table->date('batas_pembayaran_spp')->nullable(); // Batas tanggal pembayaran SPP
            $table->timestamps(); // Kolom created_at dan updated_at
        });

        // 15. Membuat tabel psb_pengumuman (dari migrasi kedua)
        // Tabel ini digunakan untuk menyimpan pengumuman hasil pendaftaran santri.
        Schema::create('psb_pengumuman', function (Blueprint $table) {
            $table->id(); // Kolom ID utama
            $table->foreignId('santri_id')->constrained('psb_pendaftaran_santri')->onDelete('cascade'); // Foreign key ke tabel psb_pendaftaran_santri
            $table->date('tanggal_pengumuman'); // Tanggal pengumuman diterbitkan
            $table->time('jam_pengumuman'); // Jam pengumuman diterbitkan
            $table->enum('status', ['diterima', 'ditolak', 'daftar_ulang']); // Status hasil pengumuman
            $table->text('catatan')->nullable(); // Catatan tambahan untuk pengumuman
            $table->string('file_pengumuman')->nullable(); // Path atau lokasi file pengumuman (misal: PDF)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Balikkan migrasi.
     *
     * @return void
     */
    public function down()
    {
        // Urutan dropIfExists penting untuk menangani foreign key constraints
        // Tabel harus di-drop dalam urutan terbalik dari pembuatannya
        Schema::dropIfExists('psb_pengumuman');
        Schema::dropIfExists('psb_sertifikat_templates');
        Schema::dropIfExists('psb_periode_daftar_ulang');
        Schema::dropIfExists('psb_rincian_biaya');
        Schema::dropIfExists('psb_rekening_settings');
        Schema::dropIfExists('wawancara_schedules');
        Schema::dropIfExists('jawaban_ujians');
        Schema::dropIfExists('hasil_ujians');
        Schema::dropIfExists('soals');
        Schema::dropIfExists('ujians');
        Schema::dropIfExists('psb_dokumen');
        Schema::dropIfExists('psb_wali_santri');
        Schema::dropIfExists('psb_pembayaran');
        Schema::dropIfExists('psb_pendaftaran_santri');
        Schema::dropIfExists('psb_periodes');
    }
};
