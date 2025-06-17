<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tabel utama untuk mencatat setiap sesi absensi yang terjadi
        Schema::create('absensi', function (Blueprint $table) {
            $table->id(); // ID unik untuk setiap sesi absensi
            $table->date('tanggal'); // Tanggal absensi dilaksanakan
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade'); // Kelas yang diabsen
            $table->foreignId('jadwal_pelajaran_id')->constrained('jadwal_pelajaran')->onDelete('cascade'); // Mata pelajaran yang diabsen
            $table->timestamps(); // Waktu data dibuat dan diupdate

            // Menambahkan unique constraint untuk memastikan satu pelajaran hanya bisa diabsen sekali per hari
            $table->unique(['tanggal', 'kelas_id', 'jadwal_pelajaran_id'], 'unique_absensi_session');
        });

        // Tabel detail untuk menyimpan status kehadiran setiap santri
        Schema::create('absensi_details', function (Blueprint $table) {
            $table->id(); // ID unik untuk setiap detail
            $table->foreignId('absensi_id')->constrained('absensi')->onDelete('cascade'); // Relasi ke tabel absensi
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade'); // Santri yang diabsen
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpa'])->default('Alpa'); // Status kehadiran santri
            $table->timestamp('jam_hadir')->nullable(); // Waktu santri scan QR atau ditandai hadir
            $table->timestamps(); // Waktu data dibuat dan diupdate
        });

        // Tabel untuk mengelola sesi QR Code
        Schema::create('qr_sessions', function (Blueprint $table) {
            $table->id(); // ID unik
            $table->foreignId('absensi_id')->constrained('absensi')->onDelete('cascade'); // Terhubung ke sesi absensi utama
            $table->string('token')->unique(); // Token unik untuk URL
            $table->timestamp('expires_at'); // Waktu kedaluwarsa QR
            $table->timestamps();
        });

        // Tabel untuk mencatat log setiap kali ada scan berhasil
        Schema::create('scan_logs', function (Blueprint $table) {
            $table->id(); // ID unik
            $table->foreignId('qr_session_id')->constrained('qr_sessions')->onDelete('cascade'); // Terhubung ke sesi QR
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade'); // Santri yang melakukan scan
            $table->timestamps(); // Waktu scan dicatat oleh created_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scan_logs');
        Schema::dropIfExists('qr_sessions');
        Schema::dropIfExists('absensi_details');
        Schema::dropIfExists('absensi');
    }
};
