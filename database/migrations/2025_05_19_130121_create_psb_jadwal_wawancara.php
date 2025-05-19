<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('psb_jadwal_wawancara', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_wawancara');
            $table->timestamp('waktu');
            $table->string('tempat');
            $table->enum('status_wawancara', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->unsignedBigInteger('santri_id');
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('psb_jadwal_wawancara');
    }
};