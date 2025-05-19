<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('psb_wali_santri', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftaran_santri_id');
            $table->string('nama_ayah');
            $table->string('pekerjaan_ayah');
            $table->string('no_hp_ayah');
            $table->string('nama_ibu');
            $table->string('pekerjaan_ibu');
            $table->string('no_hp_ibu');
            $table->string('alamat_orang_tua');
            $table->timestamps();

            $table->foreign('pendaftaran_santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('psb_wali_santri');
    }
};