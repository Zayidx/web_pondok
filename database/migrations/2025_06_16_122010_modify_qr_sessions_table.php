<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('qr_sessions', function (Blueprint $table) {
            // Hapus kolom lama jika ada
            if (Schema::hasColumn('qr_sessions', 'scanned_by_user_id')) {
                // Kita perlu menghapus foreign key constraint terlebih dahulu sebelum menghapus kolom
                // Nama constraint bisa berbeda, cek di database Anda. Format umumnya: 'table_column_foreign'
                $table->dropForeign(['scanned_by_user_id']);
                $table->dropColumn('scanned_by_user_id');
            }

            // Tambahkan kolom baru yang lebih spesifik
            // Menghubungkan ke tabel 'santris'
            $table->foreignId('santri_id')->nullable()->constrained('santris')->onDelete('cascade');
            
            // Tambahkan kolom untuk mencatat waktu scan
            $table->timestamp('scanned_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_sessions', function (Blueprint $table) {
            $table->dropForeign(['santri_id']);
            $table->dropColumn('santri_id');
            $table->dropColumn('scanned_at');

            // Jika ingin mengembalikan kolom lama (opsional)
            // $table->foreignId('scanned_by_user_id')->nullable()->constrained('users')->onDelete('cascade');
        });
    }
};
