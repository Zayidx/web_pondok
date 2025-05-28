<?php

use Illuminate\Database\Migrations\Migration;    // Mengimpor kelas utama untuk migrasi
use Illuminate\Database\Schema\Blueprint;       // Mengimpor Blueprint untuk mendefinisikan tabel
use Illuminate\Support\Facades\Schema;          // Mengimpor Schema untuk mengelola migrasi

class CreatePsbDataTable extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel-tabel baru
     * - Membuat tabel untuk periode, pendaftaran santri, wali santri, dan dokumen
     */
    public function up()
    {
        // Membuat tabel 'psb_periodes' untuk menyimpan data periode pendaftaran
        Schema::create('psb_periodes', function (Blueprint $table) {
            $table->id();                        // Kolom ID otomatis
            $table->string('nama_periode', 100); // Nama periode (misalnya Pendaftaran 2025)
            $table->date('periode_mulai');       // Tanggal mulai periode pendaftaran
            $table->date('periode_selesai');     // Tanggal selesai periode pendaftaran
            $table->enum('status_periode', ['active', 'inactive'])->default('inactive');  // Status periode
            $table->string('tahun_ajaran', 10);  // Tahun ajaran (misalnya 2025/2026)
            $table->timestamps();                // Kolom created_at dan updated_at otomatis
        });

        // Membuat tabel 'psb_pendaftaran_santri' untuk data pendaftaran santri
        Schema::create('psb_pendaftaran_santri', function (Blueprint $table) {
            $table->id();                        // Kolom ID otomatis
            $table->string('nama_jenjang', 50);  // Jenjang pendidikan (misalnya SMA)
            $table->string('nama_lengkap', 255); // Nama lengkap santri
            $table->string('nisn', 10);          // NISN
            $table->string('tempat_lahir', 255); // Tempat lahir
            $table->date('tanggal_lahir');       // Tanggal lahir
            $table->enum('jenis_kelamin', ['L', 'P']); // Jenis kelamin (Laki-laki/Perempuan)
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']); // Agama
            $table->string('email', 255);        // Email
            $table->string('no_whatsapp', 13);   // Nomor WhatsApp
            $table->string('asal_sekolah', 255); // Asal sekolah
            $table->string('tahun_lulus', 4);    // Tahun lulus (misalnya 2024, 2025)
            $table->enum('status_santri', ['reguler', 'olimpiade', 'internasional']); // Status santri
            $table->enum('status_kesantrian', ['aktif', 'nonaktif'])->default('aktif'); // Status kesantrian
            $table->unsignedBigInteger('periode_id'); // ID periode terkait
            $table->timestamps();                // Kolom created_at dan updated_at otomatis

            $table->foreign('periode_id')->references('id')->on('psb_periodes')->onDelete('restrict'); // Foreign key ke tabel psb_periodes
        });

        // Membuat tabel 'psb_wali_santri' untuk data wali santri
        Schema::create('psb_wali_santri', function (Blueprint $table) {
            $table->id();                        // Kolom ID otomatis
            $table->unsignedBigInteger('pendaftaran_santri_id'); // ID pendaftaran santri terkait
            $table->string('nama_ayah', 255);    // Nama ayah
            $table->string('pekerjaan_ayah', 255); // Pekerjaan ayah
            $table->enum('pendidikan_ayah', ['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3']); // Pendidikan ayah
            $table->enum('penghasilan_ayah', ['< 2 juta', '2-5 juta', '5-10 juta', '> 10 juta']); // Penghasilan ayah
            $table->string('nama_ibu', 255);     // Nama ibu
            $table->string('pekerjaan_ibu', 255); // Pekerjaan ibu
            $table->enum('pendidikan_ibu', ['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3']); // Pendidikan ibu
            $table->string('no_telp_ibu', 13);   // Nomor telepon ibu
            $table->text('alamat');              // Alamat lengkap
            $table->timestamps();                // Kolom created_at dan updated_at otomatis

            $table->foreign('pendaftaran_santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade'); // Foreign key ke tabel psb_pendaftaran_santri
        });

        // Membuat tabel 'psb_dokumen' untuk menyimpan data dokumen santri
        Schema::create('psb_dokumen', function (Blueprint $table) {
            $table->id();                        // Kolom ID otomatis
            $table->unsignedBigInteger('santri_id'); // ID santri terkait
            $table->string('jenis_berkas', 100); // Jenis dokumen (misalnya Pas Foto, Ijazah)
            $table->string('file_path', 255);    // Path file dokumen
            $table->date('tanggal');             // Tanggal dokumen
            $table->timestamps();                // Kolom created_at dan updated_at otomatis

            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade'); // Foreign key ke tabel psb_pendaftaran_santri
        });
    }

    /**
     * Membatalkan migrasi dengan menghapus semua tabel yang dibuat
     * - Menghapus tabel dalam urutan yang aman untuk menghindari error foreign key
     */
    public function down()
    {
        Schema::dropIfExists('psb_dokumen');
        Schema::dropIfExists('psb_wali_santri');
        Schema::dropIfExists('psb_pendaftaran_santri');
        Schema::dropIfExists('psb_periodes');
    }
}