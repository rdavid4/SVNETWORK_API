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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->integer('user_id');
            $table->integer('zipcode_id');
            $table->integer('service_id');
            $table->integer('acepted')->default(0);
            $table->integer('company_id');
            $table->string('state_iso')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
