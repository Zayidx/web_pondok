<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsbDataTable extends Migration
{
    public function up()
    {
        Schema::create('psb_periodes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenjang');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->string('status_periode')->default('active');
            $table->timestamps();
        });

        Schema::create('psb_pendaftaran_santri', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenjang');
            $table->string('nama_lengkap');
            $table->string('nik')->nullable();
            $table->string('nisn')->nullable();
            $table->string('nism')->nullable();
            $table->string('npsn')->nullable();
            $table->string('kip')->nullable();
            $table->string('no_kk')->nullable();
            $table->integer('jumlah_saudara_kandung')->nullable();
            $table->integer('anak_keberapa')->nullable();
            $table->enum('jenis_kelamin', ['putera', 'puteri']);
            $table->date('tanggal_lahir')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('no_whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->enum('status_santri', ['reguler', 'dhuafa', 'yatim_piatu'])->nullable();
            $table->enum('kewarganegaraan', ['wni', 'wna'])->nullable();
            $table->string('kelas')->nullable();
            $table->enum('pembiayaan', ['Orang Tua (Ayah/Ibu)', 'Beasiswa', 'Wali(Kakak/Paman/Bibi)'])->nullable();
            $table->string('riwayat_penyakit')->nullable();
            $table->string('hobi')->nullable();
            $table->enum('aktivitas_pendidikan', ['aktif', 'nonaktif'])->nullable();
            $table->enum('status_kesantrian', ['aktif', 'nonaktif'])->default('aktif');
            $table->unsignedBigInteger('periode_id');
            $table->timestamps();

            $table->foreign('periode_id')->references('id')->on('psb_periodes')->onDelete('cascade');
        });

        Schema::create('psb_wali_santri', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftaran_santri_id');
            $table->string('nama_kepala_keluarga')->nullable();
            $table->string('no_hp_kepala_keluarga')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->enum('status_ayah', ['hidup', 'meninggal'])->nullable();
            $table->enum('kewarganegaraan_ayah', ['wni', 'wna'])->nullable();
            $table->string('nik_ayah')->nullable();
            $table->string('tempat_lahir_ayah')->nullable();
            $table->date('tanggal_lahir_ayah')->nullable();
            $table->enum('pendidikan_terakhir_ayah', ['tidak sekolah', 'sd', 'smp', 'sma', 'slta', 'diploma', 'sarjana'])->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->decimal('penghasilan_ayah', 15, 2)->nullable();
            $table->string('no_telp_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->enum('status_ibu', ['hidup', 'meninggal'])->nullable();
            $table->enum('kewarganegaraan_ibu', ['wni', 'wna'])->nullable();
            $table->string('nik_ibu')->nullable();
            $table->string('tempat_lahir_ibu')->nullable();
            $table->date('tanggal_lahir_ibu')->nullable();
            $table->enum('pendidikan_terakhir_ibu', ['tidak sekolah', 'sd', 'smp', 'sma', 'slta', 'diploma', 'sarjana'])->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->decimal('penghasilan_ibu', 15, 2)->nullable();
            $table->string('no_telp_ibu')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('status_kepemilikan_rumah')->nullable();
            $table->text('alamat')->nullable();
            $table->enum('status_orang_tua', ['kawin', 'cerai hidup', 'cerai mati'])->nullable();
            $table->timestamps();

            $table->foreign('pendaftaran_santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
        });

        Schema::create('psb_dokumen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('santri_id');
            $table->string('jenis_berkas');
            $table->string('file_path');
            $table->date('tanggal')->nullable();
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
        });

        Schema::create('psb_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->decimal('jumlah', 15, 2)->nullable();
            $table->date('tanggal_bayar');
            $table->string('bukti_transfer');
            $table->enum('status_pembayaran', ['pending', 'paid', 'failed'])->default('pending');
            $table->unsignedBigInteger('santri_id');
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
        });
        Schema::create('psb_jadwal_wawancara', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('santri_id');
            $table->date('tanggal_wawancara');
            $table->time('jam_wawancara');
            $table->enum('mode', ['online', 'offline']);
            $table->string('link_online')->nullable();
            $table->string('lokasi_offline')->nullable();
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('psb_pendaftaran_santri')->onDelete('cascade');
        });
    }

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