<?php

use Illuminate\Database\Migrations\Migration;    // Mengimpor kelas utama untuk migrasi
use Illuminate\Database\Schema\Blueprint;       // Mengimpor Blueprint untuk mendefinisikan tabel
use Illuminate\Support\Facades\Schema;          // Mengimpor Schema untuk mengelola migrasi

class CreatePsbDataTable extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel-tabel baru
     * - Membuat tabel untuk periode, pendaftaran santri, wali santri, dokumen, pembayaran, dan jadwal wawancara
     */
    public function up()
    {
        // Membuat tabel 'psb_periodes' untuk menyimpan data periode pendaftaran
        Schema::create('psb_periodes', function (Blueprint $table) {
            $table->id();                        // Kolom ID otomatis
            $table->string('nama_jenjang');      // Nama jenjang pendidikan (misalnya SMP, SMA)
            $table->date('periode_mulai');       // Tanggal mulai periode pendaftaran
            $table->date('periode_selesai');     // Tanggal selesai periode pendaftaran
            $table->string('status_periode')->default('active');  // Status periode (default: active)
            $table->timestamps();                // Kolom created_at dan updated_at otomatis
        });

        // Membuat tabel 'psb_pendaftaran_santri' untuk data pendaftaran santri
        Schema::create('psb_pendaftaran_santri', function (Blueprint $table) {
            $table->id();                        // Kolom ID otomatis
            $table->string('nama_jenjang');      // Jenjang pendidikan yang dipilih
            $table->string('nama_lengkap');      // Nama lengkap santri
            $table->string('nik')->nullable();   // NIK (opsional)
            $table->string('nisn')->nullable();  // NISN (opsional)
            $table->string('nism')->nullable();  // NISM (opsional)
            $table->string('npsn')->nullable();  // NPSN (opsional)
            $table->string('kip')->nullable();   // Nomor KIP (opsional)
            $table->string('no_kk')->nullable(); // Nomor KK (opsional)
            $table->integer('jumlah_saudara_kandung')->nullable();  // Jumlah saudara kandung (opsional)
            $table->integer('anak_keberapa')->nullable();           // Anak keberapa (opsional)
            $table->enum('jenis_kelamin', ['putera', 'puteri']);    // Jenis kelamin (wajib)
            $table->date('tanggal_lahir')->nullable();              // Tanggal lahir (opsional)
            $table->string('tempat_lahir')->nullable();             // Tempat lahir (opsional)
            $table->string('asal_sekolah')->nullable();             // Asal sekolah (opsional)
            $table->string('no_whatsapp')->nullable();              // Nomor WhatsApp (opsional)
            $table->string('email')->nullable();                    // Email (opsional)
            $table->enum('status_santri', ['reguler', 'dhuafa', 'yatim_piatu', 'diterima', 'ditolak'])->nullable();  // Status santri (opsional)
            $table->enum('kewarganegaraan', ['wni', 'wna'])->nullable();  // Kewarganegaraan (opsional)
            $table->string('kelas')->nullable();                    // Kelas yang dipilih (opsional)
            $table->enum('pembiayaan', ['Orang Tua (Ayah/Ibu)', 'Beasiswa', 'Wali(Kakak/Paman/Bibi)'])->nullable();  // Sumber pembiayaan (opsional)
            $table->text('riwayat_penyakit')->nullable();           // Riwayat penyakit (opsional)
            $table->string('hobi')->nullable();                     // Hobi santri (opsional)
            $table->enum('aktivitas_pendidikan', ['aktif', 'nonaktif'])->nullable();  // Status aktivitas pendidikan (opsional)
            $table->enum('status_kesantrian', ['aktif', 'nonaktif'])->default('aktif');  // Status kesantrian (default: aktif)
            $table->unsignedBigInteger('periode_id');               // ID periode terkait
            $table->timestamps();                                   // Kolom created_at dan updated_at otomatis

            $table->foreign('periode_id')->references('id')->on('psb_periodes')->onDelete('cascade');  // Foreign key ke tabel psb_periodes
        });

        // Membuat tabel 'psb_wali_santri' untuk data wali santri
        Schema::create('psb_wali_santri', function (Blueprint $table) {
            $table->id();                        // Kolom ID otomatis
            $table->unsignedBigInteger('pendaftaran_santri_id');  // ID pendaftaran santri terkait
            $table->string('nama_kepala_keluarga')->nullable();   // Nama kepala keluarga (opsional)
            $table->string('no_hp_kepala_keluarga')->nullable();  // Nomor HP kepala keluarga (opsional)
            $table->string('nama_ayah')->nullable();              // Nama ayah (opsional)
            $table->enum('status_ayah', ['hidup', 'meninggal'])->nullable();  // Status ayah (opsional)
            $table->enum('kewarganegaraan_ayah', ['wni', 'wna'])->nullable();  // Kewarganegaraan ayah (opsional)
            $table->string('nik_ayah')->nullable();              // NIK ayah (opsional)
            $table->string('tempat_lahir_ayah')->nullable();     // Tempat lahir ayah (opsional)
            $table->date('tanggal_lahir_ayah')->nullable();      // Tanggal lahir ayah (opsional)
            $table->enum('pendidikan_terakhir_ayah', ['tidak sekolah', 'sd', 'smp', 'sma', 'slta', 'diploma', 'sarjana'])->nullable();  // Pendidikan ayah (opsional)
            $table->string('pekerjaan_ayah')->nullable();        // Pekerjaan ayah (opsional)
            $table->decimal('penghasilan_ayah', 15, 2)->nullable();  // Penghasilan ayah (opsional, 15 digit, 2 desimal)
            $table->string('no_telp_ayah')->nullable();          // Nomor telepon ayah (opsional)
            $table->string('nama_ibu')->nullable();              // Nama ibu (opsional)
            $table->enum('status_ibu', ['hidup', 'meninggal'])->nullable();  // Status ibu (opsional)
            $table->enum('kewarganegaraan_ibu', ['wni', 'wna'])->nullable();  // Kewarganegaraan ibu (opsional)
            $table->string('nik_ibu')->nullable();               // NIK ibu (opsional)
            $table->string('tempat_lahir_ibu')->nullable();      // Tempat lahir ibu (opsional)
            $table->date('tanggal_lahir_ibu')->nullable();       // Tanggal lahir ibu (opsional)
            $table->enum('pendidikan_terakhir_ibu', ['tidak sekolah', 'sd', 'smp', 'sma', 'slta', 'diploma', 'sarjana'])->nullable();  // Pendidikan ibu (opsional)
            $table->string('pekerjaan_ibu')->nullable();         // Pekerjaan ibu (opsional)
            $table->decimal('penghasilan_ibu', 15, 2)->nullable();  // Penghasilan ibu (opsional, 15 digit, 2 desimal)
            $table->string('no_telp_ibu')->nullable();           // Nomor telepon ibu (opsional)
            $table->string('provinsi')->nullable();              // Provinsi alamat (opsional)
            $table->string('kabupaten')->nullable();             // Kabupaten alamat (opsional)
            $table->string('kecamatan')->nullable();             // Kecamatan alamat (opsional)
            $table->string('kelurahan')->nullable();             // Kelurahan alamat (opsional)
            $table->string('rt')->nullable();                    // RT alamat (opsional)
            $table->string('rw')->nullable();                    // RW alamat (opsional)
            $table->string('kode_pos')->nullable();              // Kode pos alamat (opsional)
            $table->string('status_kepemilikan_rumah')->nullable();  // Status kepemilikan rumah (opsional)
            $table->text('alamat')->nullable();                  // Alamat lengkap (opsional)
            $table->enum('status_orang_tua', ['kawin', 'cerai hidup', 'cerai mati'])->nullable();  // Status orang tua (opsional)
            $table->timestamps();                                // Kolom created_at dan updated_at otomatis

            $table->foreign('pendaftaran_santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');  // Foreign key ke tabel psb_pendaftaran_santri
        });

        // Membuat tabel 'psb_dokumen' untuk menyimpan data dokumen santri
        Schema::create('psb_dokumen', function (Blueprint $table) {
            $table->id();                        // Kolom ID otomatis
            $table->unsignedBigInteger('santri_id');  // ID santri terkait
            $table->string('jenis_berkas');      // Jenis dokumen (misalnya foto, ijazah)
            $table->string('file_path');         // Path file dokumen
            $table->date('tanggal')->nullable(); // Tanggal dokumen (opsional)
            $table->timestamps();                // Kolom created_at dan updated_at otomatis

            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');  // Foreign key ke tabel psb_pendaftaran_santri
        });

        // Membuat tabel 'psb_pembayaran' untuk data pembayaran
        Schema::create('psb_pembayaran', function (Blueprint $table) {
            $table->id();                        // Kolom ID otomatis
            $table->decimal('jumlah', 15, 2)->nullable();  // Jumlah pembayaran (opsional, 15 digit, 2 desimal)
            $table->date('tanggal_bayar');       // Tanggal pembayaran
            $table->string('bukti_transfer');    // Bukti transfer pembayaran
            $table->enum('status_pembayaran', ['pending', 'paid', 'failed'])->default('pending');  // Status pembayaran (default: pending)
            $table->unsignedBigInteger('santri_id');  // ID santri terkait
            $table->timestamps();                // Kolom created_at dan updated_at otomatis

            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');  // Foreign key ke tabel psb_pendaftaran_santri
        });

        // Membuat tabel 'psb_jadwal_wawancara' untuk data jadwal wawancara
        Schema::create('psb_jadwal_wawancara', function (Blueprint $table) {
            $table->id();                        // Kolom ID otomatis
            $table->unsignedBigInteger('santri_id');  // ID santri terkait
            $table->date('tanggal_wawancara');   // Tanggal wawancara
            $table->time('jam_wawancara');       // Jam wawancara
            $table->enum('mode', ['online', 'offline']);  // Mode wawancara
            $table->string('link_online')->nullable();    // Link untuk wawancara online (opsional)
            $table->string('lokasi_offline')->nullable(); // Lokasi untuk wawancara offline (opsional)
            $table->timestamps();                // Kolom created_at dan updated_at otomatis

            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');  // Foreign key ke tabel psb_pendaftaran_santri
        });
    }

    /**
     * Membatalkan migrasi dengan menghapus semua tabel yang dibuat
     * - Menghapus tabel dalam urutan yang aman untuk menghindari error foreign key
     */
    public function down()
    {
        Schema::dropIfExists('psb_pembayaran');
        Schema::dropIfExists('psb_dokumen');
        Schema::dropIfExists('psb_wali_santri');
        Schema::dropIfExists('psb_pendaftaran_santri');
        Schema::dropIfExists('psb_periodes');
        Schema::dropIfExists('psb_jadwal_wawancara');
    }
}