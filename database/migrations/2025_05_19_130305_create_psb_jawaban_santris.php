<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
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
    }

    public function down()
    {
        Schema::dropIfExists('psb_jawaban_santris');
    }
};