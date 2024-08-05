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
        Schema::create('company_service_zip', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('service_id');
            $table->string('region_text');
            $table->string('state_iso')->nullable();
            $table->boolean('active')->default(0);
            $table->integer('zipcode_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_service_zip');
    }
};
