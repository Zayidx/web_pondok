<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReasonRejectedToPsbPendaftaranSantri extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('psb_pendaftaran_santri', function (Blueprint $table) {
            $table->text('reason_rejected')->nullable()->after('status_santri');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psb_pendaftaran_santri', function (Blueprint $table) {
            $table->dropColumn('reason_rejected');
        });
    }
}