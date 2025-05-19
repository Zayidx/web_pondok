<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('psb_soals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ujian_id');
            $table->integer('pertanyaan');
            $table->text('jenis');
            $table->timestamps();

            $table->foreign('ujian_id')->references('id')->on('psb_ujians')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('psb_soals');
    }
};