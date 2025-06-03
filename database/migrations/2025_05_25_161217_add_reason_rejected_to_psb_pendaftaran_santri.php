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
            if (!Schema::hasColumn('psb_pendaftaran_santri', 'reason_rejected')) {
                $table->text('reason_rejected')->nullable()->after('status_santri');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psb_pendaftaran_santri', function (Blueprint $table) {
            if (Schema::hasColumn('psb_pendaftaran_santri', 'reason_rejected')) {
                $table->dropColumn('reason_rejected');
            }
        });
    }
}
