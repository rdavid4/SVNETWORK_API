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
        Schema::create('zipcodes', function (Blueprint $table) {
            $table->id();
            $table->string('iso')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('location')->nullable();
            $table->string('state')->nullable();
            $table->string('state_iso')->nullable();
            $table->string('region')->nullable();
            $table->string('id_region')->nullable();
            $table->string('MyUnknownColumn')->nullable();
            $table->string('MyUnknownColumn_')->nullable();
            $table->float('lat')->nullable();
            $table->float('lon')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zipcodes');
    }
};
