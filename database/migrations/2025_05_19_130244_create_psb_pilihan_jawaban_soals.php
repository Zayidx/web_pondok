<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('psb_pilihan_jawaban_soals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('soal_id');
            $table->string('opsi');
            $table->text('teks_jawaban');
            $table->boolean('jawaban_benar');
            $table->timestamps();

            $table->foreign('soal_id')->references('id')->on('psb_soals')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('psb_pilihan_jawaban_soals');
    }
};