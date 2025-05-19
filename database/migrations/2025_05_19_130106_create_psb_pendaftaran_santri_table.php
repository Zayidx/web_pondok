<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('psb_pendaftaran_santri', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenjang');
            $table->string('nik');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir');
            $table->text('alamat_jenjang');
            $table->string('asal_sekolah');
            $table->string('tahun_jurus');
            $table->string('no_whatsapp');
            $table->string('email');
            $table->enum('status_pendaftaran', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('periode_id');
            $table->timestamps();

            $table->foreign('periode_id')->references('id')->on('psb_periodes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('psb_pendaftaran_santri');
    }
};