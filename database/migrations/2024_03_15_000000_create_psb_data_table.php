<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Membuat tabel psb_periodes
        Schema::create('psb_periodes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_periode');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->enum('status_periode', ['active', 'inactive'])->default('inactive');
            $table->string('tahun_ajaran');
            $table->enum('tipe_periode', ['pendaftaran_baru', 'daftar_ulang', 'ujian_masuk', 'wawancara'])->default('pendaftaran_baru');
            $table->timestamps();
        });

         // 2. Membuat tabel psb_pendaftaran_santri
         Schema::create('psb_pendaftaran_santri', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenjang')->nullable();
            $table->string('nama_lengkap');
            $table->string('alamat')->nullable();
            $table->string('nisn')->unique();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama');
            $table->string('no_whatsapp')->nullable();
            $table->string('email')->unique();
            $table->string('asal_sekolah');
            $table->string('tahun_lulus')->nullable();
            $table->enum('tipe_pendaftaran', ['reguler', 'olimpiade', 'internasional'])->nullable();
            $table->enum('status_santri', ['menunggu', 'wawancara', 'sedang_ujian', 'diterima', 'ditolak', 'daftar_ulang'])->default('menunggu');
            $table->text('reason_rejected')->nullable();
            $table->date('tanggal_wawancara')->nullable();
            $table->time('jam_wawancara')->nullable();
            $table->enum('mode', ['online', 'offline'])->nullable();
            $table->string('link_online', 255)->nullable();
            $table->string('lokasi_offline', 255)->nullable();
            $table->string('status_kesantrian')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('no_hp_ortu')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('no_telp_ibu')->nullable();
            $table->string('alamat_ortu')->nullable();
            $table->enum('status', ['daftar', 'verifikasi', 'ujian', 'wawancara', 'diterima', 'ditolak'])->default('daftar');
            $table->rememberToken();
            $table->string('password')->nullable();
            $table->enum('status_pembayaran', ['pending', 'verified', 'rejected'])->nullable();
            $table->date('tanggal_pembayaran')->nullable();

            //  $table->enum('status_pembayaran', ['pending', 'verified', 'rejected'])->nullable();
            // =================================================================
            // PENAMBAHAN KOLOM BARU UNTUK NILAI RATA-RATA (SUDAH ADA)
            // =================================================================
            $table->decimal('rata_rata_ujian', 5, 2)->nullable()->comment('Menyimpan nilai rata-rata dari semua ujian');
            
            // =================================================================
            // PENAMBAHAN KOLOM BARU UNTUK TOTAL NILAI KESELURUHAN UJIAN (BARU)
            // =================================================================
            $table->decimal('total_nilai_semua_ujian', 8, 2)->default(0)->comment('Menyimpan total nilai dari semua ujian yang telah diselesaikan');


            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->foreignId('periode_id')->constrained('psb_periodes')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('psb_pembayaran', function (Blueprint $table) {
            // Kolom ID unik untuk setiap pembayaran
            $table->id();

            // Foreign key yang terhubung ke santri yang melakukan pembayaran
            $table->foreignId('santri_id')->constrained('psb_pendaftaran_santri')->onDelete('cascade');

            // Nominal yang dibayarkan, sesuai dengan 'nominal' di PendaftaranUlang.php
            $table->decimal('nominal', 12, 2);

            // Tanggal saat santri melakukan transfer
            $table->date('tanggal_pembayaran');

            // Bank asal transfer
            $table->string('bank_pengirim');

            // Nama pemilik rekening pengirim
            $table->string('nama_pengirim');

            // Path atau lokasi file bukti pembayaran yang diunggah
            $table->string('bukti_pembayaran');
            $table->enum('status_pembayaran', ['pending', 'verified', 'rejected'])->nullable();
           
            
            // Kolom 'created_at' dan 'updated_at' standar
            $table->timestamps();
        });
        // 3. Membuat tabel psb_wali_santri (gabungan semua perubahan)
        Schema::create('psb_wali_santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_santri_id')->constrained('psb_pendaftaran_santri')->onDelete('cascade');
            $table->string('nama_wali');
            $table->enum('hubungan', ['ayah', 'ibu', 'wali'])->default('wali');
            $table->string('pekerjaan');
            $table->string('no_hp');
            $table->string('alamat');
            // Kolom tambahan hasil alter
            $table->string('nama_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('pendidikan_ayah')->nullable();
            $table->string('penghasilan_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('pendidikan_ibu')->nullable();
            $table->string('no_telp_ibu')->nullable();
            $table->timestamps();
        });

        // 4. Membuat tabel psb_dokumen
        Schema::create('psb_dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('psb_pendaftaran_santri')->onDelete('cascade');
            $table->string('jenis_berkas');
            $table->string('nama_berkas')->nullable();
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 5. Membuat tabel ujians
        Schema::create('ujians', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ujian'); 
            $table->string('mata_pelajaran');
            $table->foreignId('periode_id')->constrained('psb_periodes')->onDelete('cascade');
            $table->date('tanggal_ujian');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->enum('status_ujian', ['draft', 'aktif', 'selesai'])->default('draft');
            $table->timestamps();
        });

        // 6. Membuat tabel soals
        Schema::create('soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained('ujians')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->enum('tipe_soal', ['pg', 'essay'])->default('pg');
            $table->json('opsi')->nullable()->comment('Format: [{"teks": "Teks opsi", "bobot": 100}, ...]');
            $table->string('kunci_jawaban')->nullable()->comment('Untuk PG: A,B,C,D');
            $table->integer('poin')->default(100)->comment('Poin maksimal untuk soal essay');
            $table->timestamps();
        });



        // 7. Membuat tabel hasil_ujians
        Schema::create('hasil_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained('ujians')->onDelete('cascade');
            $table->foreignId('santri_id')->constrained('psb_pendaftaran_santri')->onDelete('cascade');
            $table->decimal('nilai_akhir', 5, 2)->default(0);
            $table->enum('status', ['belum_mulai', 'sedang_mengerjakan', 'selesai'])->default('belum_mulai');
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->timestamps();
        });
        
        // 8. Membuat tabel jawaban_ujians
        Schema::create('jawaban_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_ujian_id')->constrained('hasil_ujians')->onDelete('cascade');
            $table->foreignId('soal_id')->constrained('soals')->onDelete('cascade');
            $table->text('jawaban')->nullable();
            $table->integer('nilai')->nullable(); 
            $table->text('komentar')->nullable(); 
            $table->timestamps();
        });


        // 9. Membuat tabel wawancara_schedules
        Schema::create('wawancara_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('psb_pendaftaran_santri')->onDelete('cascade');
            $table->dateTime('jadwal_wawancara');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Tabel untuk pengaturan rekening dan biaya
        Schema::create('psb_rekening_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank');
            $table->string('nomor_rekening');
            $table->string('atas_nama');
            $table->string('catatan_transfer');
            $table->boolean('is_active')->default(true); // Pastikan kolom ini ada
            $table->timestamps();
        });

        // Tabel untuk rincian biaya
        Schema::create('psb_rincian_biaya', function (Blueprint $table) {
            $table->id();
            $table->string('nama_biaya');
            $table->decimal('jumlah', 12, 2); // Kolom 'jumlah' bukan 'nominal'
            $table->string('tahun_ajaran'); // Pastikan kolom ini ada
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel untuk periode pendaftaran ulang
        Schema::create('psb_periode_daftar_ulang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_periode'); // Pastikan kolom ini ada
            $table->date('tanggal_mulai'); // Pastikan kolom ini ada
            $table->date('tanggal_selesai'); // Pastikan kolom ini ada
            $table->string('tahun_ajaran'); // Pastikan kolom ini ada
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Memperbarui psb_sertifikat_templates table
        Schema::create('psb_sertifikat_templates', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pesantren');
            $table->string('nama_yayasan');
            $table->text('alamat_pesantren');
            $table->string('telepon_pesantren'); // Mengubah 'nomor_telepon' menjadi 'telepon_pesantren' agar sesuai dengan seeder
            $table->string('email_pesantren');
            $table->string('logo')->nullable();
            $table->string('ttd_direktur')->nullable();
            $table->string('ttd_admin')->nullable();
            $table->text('catatan_penting');
            $table->string('nama_direktur');
            $table->string('nip_direktur');
            $table->string('nama_kepala_admin');
            $table->string('nip_kepala_admin');
            // Menambahkan kolom baru yang diperlukan oleh seeder
            $table->string('tahun_ajaran')->nullable();
            $table->date('tanggal_orientasi')->nullable();
            $table->date('batas_pembayaran_spp')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        // Urutan dropIfExists penting untuk menangani foreign key constraints
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
        Schema::dropIfExists('psb_pendaftaran_santri');
        Schema::dropIfExists('psb_periodes');
    }
};
