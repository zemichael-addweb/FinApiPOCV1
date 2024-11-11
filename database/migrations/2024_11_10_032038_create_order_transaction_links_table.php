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
        Schema::create('order_transaction_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('transaction_id');
            $table->boolean('paid')->default(false);
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('shopify_orders')
                ->onDelete('cascade');

            $table->foreign('transaction_id')
                ->references('id')
                ->on('finapi_transactions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_transaction_links');
    }
};
