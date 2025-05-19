<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('psb_penilaian_essays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jawaban_santri_id');
            $table->float('skor_diberikan');
            $table->text('catatan_penilaian');
            $table->timestamps();

            $table->foreign('jawaban_santri_id')->references('id')->on('psb_jawaban_santris')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('psb_penilaian_essays');
    }
};