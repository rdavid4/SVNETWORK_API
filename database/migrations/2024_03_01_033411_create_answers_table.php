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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->integer('question_id');
            $table->integer('answer_type_id');
            $table->integer('comparator_id')->nullable();
            $table->integer('meassure_id')->nullable();
            $table->integer('order')->nullable();
            $table->integer('value_1')->nullable();
            $table->integer('value_2')->nullable();
            $table->string('text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
