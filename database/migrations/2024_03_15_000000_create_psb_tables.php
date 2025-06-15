<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create wawancara table
        Schema::create('wawancara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('pendaftaran_santris')->onDelete('cascade');
            $table->date('tanggal_wawancara');
            $table->time('jam_wawancara');
            $table->enum('mode_wawancara', ['online', 'offline']);
            $table->string('link_meeting')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'selesai', 'batal'])->default('pending');
            $table->timestamps();
        });

        // Create ujian table
        Schema::create('ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('pendaftaran_santris')->onDelete('cascade');
            $table->json('jawaban');
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->integer('durasi');
            $table->enum('status', ['sedang_ujian', 'selesai'])->default('sedang_ujian');
            $table->timestamps();
        });

        // Create psb_pengumuman table
        Schema::create('psb_pengumuman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('pendaftaran_santris')->onDelete('cascade');
            $table->date('tanggal_pengumuman');
            $table->time('jam_pengumuman');
            $table->enum('status', ['diterima', 'ditolak', 'daftar_ulang']);
            $table->text('catatan')->nullable();
            $table->string('file_pengumuman')->nullable();
            $table->timestamps();
        });

        // Create daftar_ulang table
        Schema::create('daftar_ulang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('pendaftaran_santris')->onDelete('cascade');
            $table->decimal('nominal_pembayaran', 12, 2);
            $table->date('tanggal_pembayaran');
            $table->string('bank_pengirim');
            $table->string('nama_pengirim');
            $table->string('bukti_pembayaran');
            $table->enum('status_pembayaran', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('daftar_ulang');
        Schema::dropIfExists('psb_pengumuman');
        Schema::dropIfExists('ujian');
        Schema::dropIfExists('wawancara');
    }
}; 