<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, modify any existing records that might conflict with the new enum values
        DB::table('psb_pendaftaran_santri')
            ->whereNotIn('status_santri', ['menunggu', 'wawancara', 'sedang_ujian', 'diterima', 'ditolak'])
            ->update(['status_santri' => 'menunggu']);

        // Now modify the enum
        DB::statement("ALTER TABLE psb_pendaftaran_santri MODIFY COLUMN status_santri ENUM('menunggu', 'wawancara', 'sedang_ujian', 'diterima', 'ditolak') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, modify any records with the values we're removing
        DB::table('psb_pendaftaran_santri')
            ->whereIn('status_santri', ['wawancara', 'sedang_ujian'])
            ->update(['status_santri' => 'menunggu']);

        // Now revert the enum
        DB::statement("ALTER TABLE psb_pendaftaran_santri MODIFY COLUMN status_santri ENUM('menunggu', 'diterima', 'ditolak') NULL");
    }
};
