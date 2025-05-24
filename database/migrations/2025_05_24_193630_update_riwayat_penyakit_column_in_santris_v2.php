<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::table('santris', function (Blueprint $table) {
                $table->text('riwayat_penyakit')->nullable()->change();
            });
        }

        public function down()
        {
            Schema::table('santris', function (Blueprint $table) {
                $table->string('riwayat_penyakit', 255)->nullable()->change();
            });
        }
    };