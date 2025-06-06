<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('soals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ujian_id');
            $table->enum('tipe_soal', ['pg', 'essay']);
            $table->text('pertanyaan');
            $table->json('opsi')->nullable();
            $table->integer('kunci_jawaban')->nullable();
            $table->timestamps();

            $table->foreign('ujian_id')->references('id')->on('ujians')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('soals');
    }
}; 