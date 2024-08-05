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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->morphs('imageable');
			$table->string('slug')->nullable();
			$table->string('filename')->nullable();
            $table->string('mime_type')->nullable();
			$table->string('extension')->nullable();
			$table->string('width')->nullable();
			$table->string('height')->nullable();
            $table->text('description')->nullable();
            $table->integer('size')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
