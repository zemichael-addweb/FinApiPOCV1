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
        Schema::rename('payment_forms', 'finapi_forms');

        Schema::table('finapi_forms', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finapi_forms', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_id')->nullable(false)->change();
        });

        Schema::rename('finapi_forms', 'payment_forms');
    }
};
