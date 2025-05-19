<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsbDataTable extends Migration
{
    public function up()
    {
        // Tabel psb_periodes
        Schema::create('psb_periodes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenjang');
            $table->string('periode_mulai');
            $table->string('periode_selesai');
            $table->string('status_periode')->default('active');
            $table->timestamps();
        });

        // Tabel psb_pendaftaran_santri
        Schema::create('psb_pendaftaran_santri', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenjang');
            $table->string('nik');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir');
            $table->text('alamat_jenjang');
            $table->string('asal_sekolah');
            $table->string('tahun_jurus');
            $table->string('no_whatsapp');
            $table->string('email');
            $table->enum('status_pendaftaran', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('periode_id');
            $table->timestamps();

            $table->foreign('periode_id')->references('id')->on('psb_periodes')->onDelete('cascade');
        });

        // Tabel psb_wali_santri
        Schema::create('psb_wali_santri', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftaran_santri_id');
            $table->string('nama_ayah');
            $table->string('pekerjaan_ayah');
            $table->string('no_hp_ayah');
            $table->string('nama_ibu');
            $table->string('pekerjaan_ibu');
            $table->string('no_hp_ibu');
            $table->string('alamat_orang_tua');
            $table->timestamps();

            $table->foreign('pendaftaran_santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
        });

        // Tabel psb_jadwal_wawancara
        Schema::create('psb_jadwal_wawancara', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_wawancara');
            $table->timestamp('waktu');
            $table->string('tempat');
            $table->enum('status_wawancara', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->unsignedBigInteger('santri_id');
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
 livestream: https://www.youtube.com/watch?v=2c3tLKBZqDs&list=PLhQjrBD2T382VRUw5ZpSxQSWEzfMgpMdF
        });

        // Tabel psb_pembayaran
        Schema::create('psb_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('jumlah');
            $table->timestamp('tanggal_bayar');
            $table->string('bukti_transfer');
            $table->enum('status_pembayaran', ['pending', 'paid', 'failed'])->default('pending');
            $table->unsignedBigInteger('santri_id');
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
        });

        // Tabel psb_dokumen
        Schema::create('psb_dokumen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('berkas_pendaftaran_id');
            $table->string('jenis_berkas');
            $table->string('tanggal');
            $table->unsignedBigInteger('santri_id');
            $table->timestamps();

            $table->foreign('berkas_pendaftaran_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
        });

        // Tabel psb_ujians
        Schema::create('psb_ujians', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ujian');
            $table->text('deskripsi');
            $table->enum('jenis', ['pilhan_ganda', 'essay', 'campuran']);
            $table->integer('durasi_menit');
            $table->datetime('waktu_akif_dari');
            $table->datetime('waktu_akif_sampai');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        // Tabel psb_soals
        Schema::create('psb_soals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ujian_id');
            $table->integer('pertanyaan');
            $table->text('jenis');
            $table->timestamps();

            $table->foreign('ujian_id')->references('id')->on('psb_ujians')->onDelete('cascade');
        });

        // Tabel psb_pilihan_jawaban_soals
        Schema::create('psb_pilihan_jawaban_soals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('soal_id');
            $table->string('opsi');
            $table->text('teks_jawaban');
            $table->boolean('jawaban_benar');
            $table->timestamps();

            $table->foreign('soal_id')->references('id')->on('psb_soals')->onDelete('cascade');
        });

        // Tabel psb_ujian_santris
        Schema::create('psb_ujian_santris', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('santri_id');
            $table->unsignedBigInteger('ujian_id');
            $table->enum('status', ['belum', 'mulai', 'sedang', 'ujian', 'selesai']);
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->float('skor_akhir')->nullable();
            $table->text('catatan_penilaian')->nullable();
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
            $table->foreign('ujian_id')->references('id')->on('psb_ujians')->onDelete('cascade');
        });

        // Tabel psb_jawaban_santris
        Schema::create('psb_jawaban_santris', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('santri_id');
            $table->unsignedBigInteger('ujian_id');
            $table->unsignedBigInteger('soal_id');
            $table->unsignedBigInteger('jawaban_pg_id')->nullable();
            $table->text('jawaban_essay')->nullable();
            $table->float('skor_diberikan')->nullable();
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
            $table->foreign('ujian_id')->references('id')->on('psb_ujians')->onDelete('cascade');
            $table->foreign('soal_id')->references('id')->on('psb_soals')->onDelete('cascade');
            $table->foreign('jawaban_pg_id')->references('id')->on('psb_pilihan_jawaban_soals')->onDelete('cascade');
        });

        // Tabel psb_penilaian_essays
        Schema::create('psb_penilaian_essays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jawaban_santri_id');
            $table->float('skor_diberikan');
            $table->text('catatan_penilaian');
            $table->timestamps();

            $table->foreign('jawaban_santri_id')->references('id')->on('psb_jawaban_santris')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('psb_penilaian_essays');
        Schema::dropIfExists('psb_jawaban_santris');
        Schema::dropIfExists('psb_ujian_santris');
        Schema::dropIfExists('psb_pilihan_jawaban_soals');
        Schema::dropIfExists('psb_soals');
        Schema::dropIfExists('psb_ujians');
        Schema::dropIfExists('psb_dokumen');
        Schema::dropIfExists('psb_pembayaran');
        Schema::dropIfExists('psb_jadwal_wawancara');
        Schema::dropIfExists('psb_wali_santri');
        Schema::dropIfExists('psb_pendaftaran_santri');
        Schema::dropIfExists('psb_periodes');
    }
}