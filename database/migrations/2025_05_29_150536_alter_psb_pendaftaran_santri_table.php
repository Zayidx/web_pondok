<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPsbPendaftaranSantriTable extends Migration
{
    public function up()
    {
        Schema::table('psb_pendaftaran_santri', function (Blueprint $table) {
            // Add tipe_pendaftaran column if it doesn't exist
            if (!Schema::hasColumn('psb_pendaftaran_santri', 'tipe_pendaftaran')) {
                $table->enum('tipe_pendaftaran', ['reguler', 'olimpiade', 'internasional'])->nullable()->after('tahun_lulus');
            }

            // Add interview-related columns if they don't exist
            if (!Schema::hasColumn('psb_pendaftaran_santri', 'tanggal_wawancara')) {
                $table->date('tanggal_wawancara')->nullable()->after('status_santri');
            }
            if (!Schema::hasColumn('psb_pendaftaran_santri', 'jam_wawancara')) {
                $table->time('jam_wawancara')->nullable()->after('tanggal_wawancara');
            }
            if (!Schema::hasColumn('psb_pendaftaran_santri', 'mode')) {
                $table->enum('mode', ['online', 'offline'])->nullable()->after('jam_wawancara');
            }
            if (!Schema::hasColumn('psb_pendaftaran_santri', 'link_online')) {
                $table->string('link_online')->nullable()->after('mode');
            }
            if (!Schema::hasColumn('psb_pendaftaran_santri', 'lokasi_offline')) {
                $table->string('lokasi_offline', 255)->nullable()->after('link_online');
            }
            if (!Schema::hasColumn('psb_pendaftaran_santri', 'reason_rejected')) {
                $table->text('reason_rejected')->nullable()->after('lokasi_offline');
            }
        });
    }

    public function down()
    {
        Schema::table('psb_pendaftaran_santri', function (Blueprint $table) {
            // Drop added columns if they exist
            $columns = [
                'tipe_pendaftaran',
                'tanggal_wawancara',
                'jam_wawancara',
                'mode',
                'link_online',
                'lokasi_offline',
                'reason_rejected'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('psb_pendaftaran_santri', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}