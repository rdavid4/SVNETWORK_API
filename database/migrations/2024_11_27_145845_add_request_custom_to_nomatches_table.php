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
        Schema::table('no_matches', function (Blueprint $table) {
            $table->dateTime('requested_lead')->nullable()->after('done');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('no_matches', function (Blueprint $table) {
            $table->dropColumn('requested');
        });
    }
};
