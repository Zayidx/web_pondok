<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jawaban_ujians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hasil_ujian_id');
            $table->unsignedBigInteger('soal_id');
            $table->integer('jawaban')->nullable(); // index dari opsi yang dipilih
            $table->timestamps();

            $table->foreign('hasil_ujian_id')->references('id')->on('hasil_ujians')->onDelete('cascade');
            $table->foreign('soal_id')->references('id')->on('soals')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jawaban_ujians');
    }
}; 