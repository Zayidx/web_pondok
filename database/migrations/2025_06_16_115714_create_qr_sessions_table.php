<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_sessions', function (Blueprint $table) {
            // Kolom ID unik untuk setiap sesi QR
            $table->id();
            // Kolom untuk menyimpan token unik yang akan menjadi isi QR code
            $table->string('token')->unique();
            // Kolom untuk menyimpan ID user (santri) yang melakukan scan, bisa null jika belum ada yang scan
            $table->foreignId('scanned_by_user_id')->nullable()->constrained('users')->onDelete('cascade');
            // Kolom untuk waktu kedaluwarsa QR code
            $table->timestamp('expires_at');
            // Kolom timestamp bawaan Laravel (created_at dan updated_at)
            $table->timestamps();
        });

        Schema::create('scan_logs', function (Blueprint $table) {
            $table->id();
            // Kolom untuk menghubungkan ke sesi QR mana log ini berasal
            $table->foreignId('qr_session_id')->constrained('qr_sessions')->onDelete('cascade');
            // Kolom untuk menghubungkan ke santri mana yang melakukan scan
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_sessions');
        Schema::dropIfExists('scan_logs');
    }
};