<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
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
    }

    public function down()
    {
        Schema::dropIfExists('psb_ujian_santris');
    }
};