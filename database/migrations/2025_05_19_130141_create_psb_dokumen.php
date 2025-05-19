<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('psb_dokumen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('berkas_pendaftaran_id');
            $table->string('jenis_berkas');
            $table->string('tanggal');
            $table->unsignedBigInteger('santri_id');
            $table->timestamps();

            $table->foreign('berkas_pendaftaran_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('psb_dokumen');
    }
};