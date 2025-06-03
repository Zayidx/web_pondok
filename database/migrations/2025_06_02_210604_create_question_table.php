<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['essay', 'multiple-choice']);
            $table->text('question');
            $table->integer('points')->default(5);
            $table->string('image_path')->nullable();
            $table->text('correct_answer')->nullable();
            $table->json('options')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
};