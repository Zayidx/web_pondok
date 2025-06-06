<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPoinColumnToSoalsTable extends Migration
{
    public function up()
    {
        Schema::table('soals', function (Blueprint $table) {
            $table->integer('poin')->default(1)->after('pertanyaan');
        });
    }

    public function down()
    {
        Schema::table('soals', function (Blueprint $table) {
            $table->dropColumn('poin');
        });
    }
} 