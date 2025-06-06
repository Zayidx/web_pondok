<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ujians', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ujian');
            $table->string('mata_pelajaran');
            $table->unsignedBigInteger('periode_id');
            $table->date('tanggal_ujian');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('status_ujian')->default('draft'); // draft, aktif, selesai
            $table->timestamps();

            $table->foreign('periode_id')->references('id')->on('psb_periodes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ujians');
    }
}; 