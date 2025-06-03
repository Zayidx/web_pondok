<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePsbPendaftaranSantriTable extends Migration
{
    public function up()
    {
        Schema::table('psb_pendaftaran_santri', function (Blueprint $table) {
            // Rename status_santri to tipe_pendaftaran if it exists and hasn't been renamed yet
            if (Schema::hasColumn('psb_pendaftaran_santri', 'status_santri') && !Schema::hasColumn('psb_pendaftaran_santri', 'tipe_pendaftaran')) {
                $table->renameColumn('status_santri', 'tipe_pendaftaran');
            }

            // Add status_santri column if it doesn't exist
            if (!Schema::hasColumn('psb_pendaftaran_santri', 'status_santri')) {
                $table->enum('status_santri', ['menunggu', 'diterima', 'ditolak'])->nullable()->after('tipe_pendaftaran');
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

        // Update existing data: move status_santri values to tipe_pendaftaran
        if (Schema::hasColumn('psb_pendaftaran_santri', 'tipe_pendaftaran')) {
            \DB::statement("UPDATE psb_pendaftaran_santri SET tipe_pendaftaran = 'reguler' WHERE tipe_pendaftaran IN ('reguler', 'olimpiade', 'internasional')");
        }
        if (Schema::hasColumn('psb_pendaftaran_santri', 'status_santri')) {
            \DB::statement("UPDATE psb_pendaftaran_santri SET status_santri = 'menunggu' WHERE tipe_pendaftaran IS NOT NULL");
        }
    }

    public function down()
    {
        Schema::table('psb_pendaftaran_santri', function (Blueprint $table) {
            // Drop new columns if they exist
            $columns = [
                'status_santri',
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

            // Rename tipe_pendaftaran back to status_santri if it exists
            if (Schema::hasColumn('psb_pendaftaran_santri', 'tipe_pendaftaran')) {
                $table->renameColumn('tipe_pendaftaran', 'status_santri');
            }
        });
    }
}