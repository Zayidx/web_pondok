<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Create psb_periodes table
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

         // 2. Create psb_pendaftaran_santri table
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
            
            // Kolom untuk pendaftaran ulang
            $table->decimal('nominal_pembayaran', 12, 2)->nullable();
            $table->date('tanggal_pembayaran')->nullable();
            $table->string('bank_pengirim')->nullable();
            $table->string('nama_pengirim')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status_pembayaran', ['pending', 'verified', 'rejected'])->nullable();

            // =================================================================
            // PENAMBAHAN KOLOM BARU UNTUK NILAI RATA-RATA
            // =================================================================
            $table->decimal('rata_rata_ujian', 5, 2)->nullable()->comment('Menyimpan nilai rata-rata dari semua ujian');
            
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->foreignId('periode_id')->constrained('psb_periodes')->onDelete('cascade');
            $table->timestamps();
        });

        // 3. Create psb_wali_santri table (gabungan semua perubahan)
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

        // 4. Create psb_dokumen table
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

        // 5. Create ujians table
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

        // 6. Create soals table
        Schema::create('soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained('ujians')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->enum('tipe_soal', ['pg', 'essay'])->default('pg');
            $table->json('opsi')->nullable();
            $table->text('kunci_jawaban')->nullable();
            $table->integer('bobot_nilai')->default(1);
            $table->integer('poin')->default(1);
            $table->timestamps();
        });

        // 7. Create hasil_ujians table
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

        // 8. Create jawaban_ujians table
        Schema::create('jawaban_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_ujian_id')->constrained('hasil_ujians')->onDelete('cascade');
            $table->foreignId('soal_id')->constrained('soals')->onDelete('cascade');
            $table->text('jawaban')->nullable();
            $table->integer('nilai')->nullable();
            $table->text('komentar')->nullable();
            $table->timestamps();
        });

        // 9. Create wawancara_schedules table
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
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel untuk rincian biaya
        Schema::create('psb_rincian_biaya', function (Blueprint $table) {
            $table->id();
            $table->string('nama_biaya');
            $table->decimal('jumlah', 12, 2);
            $table->string('tahun_ajaran');
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel untuk periode pendaftaran ulang
        Schema::create('psb_periode_daftar_ulang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_periode');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('tahun_ajaran');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wawancara_schedules');
        Schema::dropIfExists('jawaban_ujians');
        Schema::dropIfExists('hasil_ujians');
        Schema::dropIfExists('soals');
        Schema::dropIfExists('ujians');
        Schema::dropIfExists('psb_dokumen');
        Schema::dropIfExists('psb_wali_santri');
        Schema::dropIfExists('psb_pendaftaran_santri');
        Schema::dropIfExists('psb_periodes');
        Schema::dropIfExists('psb_rekening_settings');
        Schema::dropIfExists('psb_rincian_biaya');
        Schema::dropIfExists('psb_periode_daftar_ulang');
    }
}; 