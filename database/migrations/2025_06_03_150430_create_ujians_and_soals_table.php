<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUjiansAndSoalsTable extends Migration
{
    public function up()
    {
        // Membuat tabel 'ujians' untuk menyimpan data ujian
        Schema::create('ujians', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ujian', 100);
            $table->string('mata_pelajaran', 100);
            $table->unsignedBigInteger('periode_id');
            $table->date('tanggal_ujian');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->enum('status_ujian', ['draft', 'aktif', 'selesai'])->default('draft');
            $table->timestamps();

            $table->foreign('periode_id')->references('id')->on('psb_periodes')->onDelete('restrict');
        });

        // Membuat tabel 'soals' untuk menyimpan soal PG dan essay
        Schema::create('soals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ujian_id');
            $table->string('tipe_soal'); // Changed from enum to string
            $table->text('pertanyaan');
            $table->json('opsi')->nullable(); // Untuk opsi PG (array opsi)
            $table->integer('kunci_jawaban')->nullable(); // Index opsi yang benar untuk PG
            $table->timestamps();

            $table->foreign('ujian_id')->references('id')->on('ujians')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('soals');
        Schema::dropIfExists('ujians');
    }
}