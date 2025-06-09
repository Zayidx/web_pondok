<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPsbWaliSantriTable extends Migration
{
    public function up()
    {
        Schema::table('psb_wali_santri', function (Blueprint $table) {
            $table->string('nama_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('pendidikan_ayah')->nullable();
            $table->string('penghasilan_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('pendidikan_ibu')->nullable();
            $table->string('no_telp_ibu')->nullable();
        });
    }

    public function down()
    {
        Schema::table('psb_wali_santri', function (Blueprint $table) {
            $table->dropColumn([
                'nama_ayah', 'pekerjaan_ayah', 'pendidikan_ayah', 'penghasilan_ayah',
                'nama_ibu', 'pekerjaan_ibu', 'pendidikan_ibu', 'no_telp_ibu'
            ]);
        });
    }
}