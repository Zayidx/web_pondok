<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hasil_ujians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('santri_id');
            $table->unsignedBigInteger('ujian_id');
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->string('status')->default('belum_mulai'); // belum_mulai, sedang_mengerjakan, selesai
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
            $table->foreign('ujian_id')->references('id')->on('ujians')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hasil_ujians');
    }
}; 