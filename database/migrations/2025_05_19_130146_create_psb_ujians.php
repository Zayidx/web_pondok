<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('psb_ujians', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ujian');
            $table->text('deskripsi');
            $table->enum('jenis', ['pilhan_ganda', 'essay', 'campuran']);
            $table->integer('durasi_menit');
            $table->datetime('waktu_akif_dari');
            $table->datetime('waktu_akif_sampai');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('psb_ujians');
    }
};