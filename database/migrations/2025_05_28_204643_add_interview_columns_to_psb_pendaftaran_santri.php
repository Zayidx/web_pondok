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
            if (!Schema::hasColumn('psb_pendaftaran_santri', 'tanggal_wawancara')) {
                $table->date('tanggal_wawancara')->nullable()->after('status_kesantrian');
            }
            if (!Schema::hasColumn('psb_pendaftaran_santri', 'jam_wawancara')) {
                $table->time('jam_wawancara')->nullable()->after('tanggal_wawancara');
            }
            if (!Schema::hasColumn('psb_pendaftaran_santri', 'mode')) {
                $table->enum('mode', ['online', 'offline'])->nullable()->after('jam_wawancara');
            }
            if (!Schema::hasColumn('psb_pendaftaran_santri', 'link_online')) {
                $table->string('link_online', 255)->nullable()->after('mode');
            }
            if (!Schema::hasColumn('psb_pendaftaran_santri', 'lokasi_offline')) {
                $table->string('lokasi_offline', 255)->nullable()->after('link_online');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psb_pendaftaran_santri', function (Blueprint $table) {
            $columns = [
                'tanggal_wawancara',
                'jam_wawancara',
                'mode',
                'link_online',
                'lokasi_offline',
            ];
            $existingColumns = array_filter($columns, function ($column) {
                return Schema::hasColumn('psb_pendaftaran_santri', $column);
            });
            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }
}