<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePsbPendaftaranSantriTable extends Migration
{
    public function up()
    {
        Schema::table('psb_pendaftaran_santri', function (Blueprint $table) {
            // Rename status_santri to tipe_pendaftaran
            $table->renameColumn('status_santri', 'tipe_pendaftaran');

            // Add new status_santri column with correct ENUM
            $table->enum('status_santri', ['menunggu', 'diterima', 'ditolak'])->nullable()->after('tipe_pendaftaran');

            // Add interview-related columns
            $table->date('tanggal_wawancara')->nullable()->after('status_santri');
            $table->time('jam_wawancara')->nullable()->after('tanggal_wawancara');
            $table->enum('mode', ['online', 'offline'])->nullable()->after('jam_wawancara');
            $table->string('link_online')->nullable()->after('mode');
            $table->string('lokasi_offline', 255)->nullable()->after('link_online');
            $table->text('reason_rejected')->nullable()->after('lokasi_offline');
        });

        // Update existing data: move status_santri values to tipe_pendaftaran
        \DB::statement("UPDATE psb_pendaftaran_santri SET tipe_pendaftaran = 'reguler' WHERE tipe_pendaftaran IN ('reguler', 'olimpiade', 'internasional')");
        \DB::statement("UPDATE psb_pendaftaran_santri SET status_santri = 'menunggu' WHERE tipe_pendaftaran IS NOT NULL");
    }

    public function down()
    {
        Schema::table('psb_pendaftaran_santri', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'status_santri',
                'tanggal_wawancara',
                'jam_wawancara',
                'mode',
                'link_online',
                'lokasi_offline',
                'reason_rejected'
            ]);

            // Rename tipe_pendaftaran back to status_santri
            $table->renameColumn('tipe_pendaftaran', 'status_santri');
        });
    }
}