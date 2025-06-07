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
            $table->timestamps();
        });

        // 2. Create psb_pendaftaran_santri table
        Schema::create('psb_pendaftaran_santri', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nisn')->unique();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('alamat');
            $table->string('no_hp');
            $table->string('email')->unique();
            $table->string('asal_sekolah');
            $table->string('tahun_lulus')->nullable();
            $table->enum('tipe_pendaftaran', ['reguler', 'olimpiade', 'internasional'])->nullable();
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('pekerjaan_ayah');
            $table->string('pekerjaan_ibu');
            $table->string('no_hp_ortu');
            $table->string('alamat_ortu');
            $table->enum('status', ['daftar', 'verifikasi', 'ujian', 'wawancara', 'diterima', 'ditolak'])->default('daftar');
            $table->enum('status_santri', ['menunggu', 'wawancara', 'sedang_ujian', 'lulus', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('reason_rejected')->nullable();
            
            // Wawancara fields
            $table->dateTime('tanggal_wawancara')->nullable();
            $table->enum('mode', ['online', 'offline'])->nullable();
            $table->string('link_online')->nullable();
            $table->string('lokasi_offline')->nullable();
            $table->text('hasil_wawancara')->nullable();
            $table->enum('status_wawancara', ['belum', 'selesai', 'tidak_hadir'])->default('belum');
            
            $table->rememberToken();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        // Create psb_wali_santri table
        Schema::create('psb_wali_santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_santri_id')->constrained('psb_pendaftaran_santri')->onDelete('cascade');
            $table->string('nama_wali');
            $table->enum('hubungan', ['ayah', 'ibu', 'wali'])->default('wali');
            $table->string('pekerjaan');
            $table->string('no_hp');
            $table->string('alamat');
            $table->timestamps();
        });

        // Create psb_dokumen table
        Schema::create('psb_dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('psb_pendaftaran_santri')->onDelete('cascade');
            $table->string('jenis_berkas');
            $table->string('nama_berkas');
            $table->string('file_path');
            $table->string('file_type');
            $table->integer('file_size');
            $table->boolean('is_verified')->default(false);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 3. Create ujians table
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

        // 4. Create soals table
        Schema::create('soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained('ujians')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->enum('tipe_soal', ['pg', 'essay'])->default('pg');
            $table->json('opsi')->nullable();
            $table->text('kunci_jawaban');
            $table->integer('bobot_nilai')->default(1);
            $table->integer('poin')->default(1);
            $table->timestamps();
        });

        // 5. Create hasil_ujians table
        Schema::create('hasil_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained('ujians')->onDelete('cascade');
            $table->foreignId('santri_id')->constrained('psb_pendaftaran_santri')->onDelete('cascade');
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->enum('status', ['belum_mulai', 'sedang_mengerjakan', 'selesai'])->default('belum_mulai');
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->timestamps();
        });

        // 6. Create jawaban_ujians table
        Schema::create('jawaban_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_ujian_id')->constrained('hasil_ujians')->onDelete('cascade');
            $table->foreignId('soal_id')->constrained('soals')->onDelete('cascade');
            $table->text('jawaban')->nullable();
            $table->integer('nilai')->nullable();
            $table->text('komentar')->nullable();
            $table->timestamps();
        });

        // 7. Create wawancara_schedules table
        Schema::create('wawancara_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('psb_pendaftaran_santri')->onDelete('cascade');
            $table->dateTime('jadwal_wawancara');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->text('catatan')->nullable();
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
    }
}; 
 