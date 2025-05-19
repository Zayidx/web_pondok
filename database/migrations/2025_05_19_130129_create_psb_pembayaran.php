<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('psb_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('jumlah');
            $table->timestamp('tanggal_bayar');
            $table->string('bukti_transfer');
            $table->enum('status_pembayaran', ['pending', 'paid', 'failed'])->default('pending');
            $table->unsignedBigInteger('santri_id');
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('psb_pembayaran');
    }
};