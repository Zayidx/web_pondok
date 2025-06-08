<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hasil_ujians', function (Blueprint $table) {
            // Ubah nilai_akhir agar default 0 dan tidak nullable
            $table->decimal('nilai_akhir', 5, 2)->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('hasil_ujians', function (Blueprint $table) {
            // Kembalikan ke nullable untuk rollback
            $table->decimal('nilai_akhir', 5, 2)->nullable()->change();
        });
    }
};