<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsbDataTable extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel-tabel baru
     * - Membuat tabel untuk periode, pendaftaran santri, wali santri, dokumen, dan ujian
     */
    public function up()
    {
        // Membuat tabel 'psb_periodes' untuk menyimpan data periode pendaftaran
        Schema::create('psb_periodes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_periode', 100);
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->enum('status_periode', ['active', 'inactive'])->default('inactive');
            $table->string('tahun_ajaran', 10);
            $table->timestamps();
        });

        // Membuat tabel 'psb_pendaftaran_santri' untuk data pendaftaran santri
        Schema::create('psb_pendaftaran_santri', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenjang', 50);
            $table->string('nama_lengkap', 255);
            $table->string('nisn', 10);
            $table->string('tempat_lahir', 255);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']);
            $table->string('email', 255);
            $table->string('no_whatsapp', 13);
            $table->string('asal_sekolah', 255);
            $table->string('tahun_lulus', 4);
            $table->enum('tipe_pendaftaran', ['reguler', 'olimpiade', 'internasional'])->nullable();
            $table->enum('status_santri', ['menunggu', 'wawancara', 'sedang_ujian', 'diterima', 'ditolak'])->nullable();
            $table->date('tanggal_wawancara')->nullable();
            $table->time('jam_wawancara')->nullable();
            $table->enum('mode', ['online', 'offline'])->nullable();
            $table->string('link_online')->nullable();
            $table->string('lokasi_offline', 255)->nullable();
            $table->text('reason_rejected')->nullable();
            $table->text('riwayat_penyakit')->nullable();
            $table->enum('status_kesantrian', ['aktif', 'nonaktif'])->default('aktif');
            $table->unsignedBigInteger('periode_id');
            $table->timestamps();

            $table->foreign('periode_id')->references('id')->on('psb_periodes')->onDelete('restrict');
        });

        // Membuat tabel 'psb_wali_santri' untuk data wali santri
        Schema::create('psb_wali_santri', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftaran_santri_id');
            $table->string('nama_ayah', 255);
            $table->string('pekerjaan_ayah', 255);
            $table->enum('pendidikan_ayah', ['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3']);
            $table->enum('penghasilan_ayah', ['< 2 juta', '2-5 juta', '5-10 juta', '> 10 juta']);
            $table->string('nama_ibu', 255);
            $table->string('pekerjaan_ibu', 255);
            $table->enum('pendidikan_ibu', ['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3']);
            $table->string('no_telp_ibu', 13);
            $table->text('alamat');
            $table->timestamps();

            $table->foreign('pendaftaran_santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
        });

        // Membuat tabel 'psb_dokumen' untuk menyimpan data dokumen santri
        Schema::create('psb_dokumen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('santri_id');
            $table->string('jenis_berkas', 100);
            $table->string('file_path', 255);
            $table->date('tanggal');
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
        });

        // Membuat tabel 'psb_questions' untuk menyimpan soal ujian
        Schema::create('psb_questions', function (Blueprint $table) {
            $table->id();
            $table->text('pertanyaan');
            $table->enum('tipe_soal', ['pg', 'essay']);
            $table->text('pilihan_a')->nullable();
            $table->text('pilihan_b')->nullable();
            $table->text('pilihan_c')->nullable();
            $table->text('pilihan_d')->nullable();
            $table->text('pilihan_e')->nullable();
            $table->string('jawaban_benar')->nullable();
            $table->integer('poin')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Membuat tabel 'psb_ujian_santri' untuk menyimpan jawaban ujian santri
        Schema::create('psb_ujian_santri', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('santri_id');
            $table->unsignedBigInteger('question_id');
            $table->text('jawaban')->nullable();
            $table->integer('poin_diperoleh')->default(0);
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('psb_questions')->onDelete('cascade');
        });
    }

    /**
     * Membatalkan migrasi dengan menghapus semua tabel yang dibuat
     * - Menghapus tabel dalam urutan yang aman untuk menghindari error foreign key
     */
    public function down()
    {
        Schema::dropIfExists('psb_ujian_santri');
        Schema::dropIfExists('psb_questions');
        Schema::dropIfExists('psb_dokumen');
        Schema::dropIfExists('psb_wali_santri');
        Schema::dropIfExists('psb_pendaftaran_santri');
        Schema::dropIfExists('psb_periodes');
    }
}
