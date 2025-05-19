<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('psb_periodes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenjang');
            $table->string('periode_mulai');
            $table->string('periode_selesai');
            $table->string('status_periode')->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('psb_periodes');
    }
};