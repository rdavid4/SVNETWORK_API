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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('stripe_payment_intent')->nullable()->after('stripe_payment_method'); // Cambia 'last_column_name' por el nombre de la última columna existente
            $table->string('match_id')->nullable()->after('stripe_payment_intent'); // Cambia 'last_column_name' por el nombre de la última columna existente
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('stripe_payment_intent');
            $table->dropColumn('match_id');
        });
    }
};
