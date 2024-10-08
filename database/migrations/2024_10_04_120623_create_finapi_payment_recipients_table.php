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
        Schema::create('finapi_payment_recipients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('iban', 34);
            $table->string('bic', 11)->nullable();
            $table->string('bank_name', 255)->nullable();
            $table->string('street', 255)->nullable();
            $table->string('house_number', 50)->nullable();
            $table->string('post_code', 10)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('country', 2)->nullable(); // ISO Country Code
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fin_payment_recipients');
    }
};
