<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInterviewColumnsToPsbPendaftaranSantri extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('psb_pendaftaran_santri', function (Blueprint $table) {
            $table->date('tanggal_wawancara')->nullable()->after('status_kesantrian');
            $table->time('jam_wawancara')->nullable()->after('tanggal_wawancara');
            $table->enum('mode', ['online', 'offline'])->nullable()->after('jam_wawancara');
            $table->string('link_online')->nullable()->after('mode');
            $table->string('lokasi_offline')->nullable()->after('link_online');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psb_pendaftaran_santri', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_wawancara',
                'jam_wawancara',
                'mode',
                'link_online',
                'lokasi_offline',
            ]);
        });
    }
}