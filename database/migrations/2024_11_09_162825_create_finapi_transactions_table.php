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
        Schema::create('finapi_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('finapi_id', 128);
            $table->unsignedBigInteger('finapi_user_id')->nullable();
            $table->unsignedBigInteger('finapi_form_id')->nullable();
            $table->unsignedBigInteger('finapi_payment_id')->nullable();
            $table->unsignedBigInteger('shopify_order_id')->nullable();
            $table->bigInteger('account_id');
            $table->date('value_date')->nullable();
            $table->date('bank_booking_date')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->nullable();
            $table->string('purpose')->nullable();
            $table->string('counterpart_name')->nullable();
            $table->string('type')->nullable();
            $table->string('shopify_confirmation_number')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();

            $table->foreign('finapi_user_id')->references('id')->on('finapi_users')->onDelete('set null');
            $table->foreign('finapi_form_id')->references('id')->on('finapi_webforms')->onDelete('set null');
            $table->foreign('finapi_payment_id')->references('id')->on('finapi_payments')->onDelete('set null');
            $table->foreign('shopify_order_id')->references('id')->on('shopify_orders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finapi_transactions');
    }
};
