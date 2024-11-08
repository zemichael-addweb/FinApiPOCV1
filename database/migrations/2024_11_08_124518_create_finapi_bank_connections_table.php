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
        Schema::create('finapi_bank_connections', function (Blueprint $table) {
            $table->id();
            $table->string('finapi_id', 128);
            $table->unsignedBigInteger('finapi_user_id')->nullable();
            $table->unsignedBigInteger('finapi_form_id')->nullable();

            $table->string('bank_name')->nullable();
            $table->string('blz')->nullable();
            $table->string('bank_group')->nullable();
            $table->json('data');

            $table->timestamps();

            $table->foreign('finapi_user_id')->references('id')->on('finapi_users')->onDelete('set null');
            $table->foreign('finapi_form_id')->references('id')->on('finapi_webforms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finapi_bank_connections');
    }
};
