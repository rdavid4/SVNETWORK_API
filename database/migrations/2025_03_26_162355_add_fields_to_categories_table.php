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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('meta_title')->nullable();
            $table->string('slug')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_title_2')->nullable();
            $table->text('meta_description2')->nullable();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'slug', 'meta_description', 'meta_title_2', 'meta_description2', 'title', 'description', 'icon', 'image']);
        });
    }
};
