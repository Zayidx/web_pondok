<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPsbPendaftaranSantriTable extends Migration
{
    public function up()
    {
        Schema::table('psb_pendaftaran_santri', function (Blueprint $table) {
            // Add tipe_pendaftaran column
            $table->enum('tipe_pendaftaran', ['reguler', 'olimpiade', 'internasional'])->nullable()->after('tahun_lulus');

            // Add interview-related columns
            $table->date('tanggal_wawancara')->nullable()->after('status_santri');
            $table->time('jam_wawancara')->nullable()->after('tanggal_wawancara');
            $table->enum('mode', ['online', 'offline'])->nullable()->after('jam_wawancara');
            $table->string('link_online')->nullable()->after('mode');
            $table->string('lokasi_offline', 255)->nullable()->after('link_online');
            $table->text('reason_rejected')->nullable()->after('lokasi_offline');
        });
    }

    public function down()
    {
        Schema::table('psb_pendaftaran_santri', function (Blueprint $table) {
            // Drop added columns
            $table->dropColumn([
                'tipe_pendaftaran',
                'tanggal_wawancara',
                'jam_wawancara',
                'mode',
                'link_online',
                'lokasi_offline',
                'reason_rejected'
            ]);
        });
    }
}
