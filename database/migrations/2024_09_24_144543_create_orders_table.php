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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            //     order_id: The Shopify order ID.
            //     order_reference: A unique reference number associated with the order (this will be used by customers to make payments).
            //     customer_name: Name of the customer (for fallback matching).
            //     order_amount: The total amount of the order.
            //     order_status: Current status of the order (pending, paid, canceled).
            //     ordered_at: The date when the order was placed.
            //     payment_deadline: A date field to track when the 7-day payment window expires.
            //     is_b2b: A boolean field to indicate if this is a B2B customer order.
            $table->string('order_id')->unique();
            $table->string('order_reference')->unique();
            $table->string('customer_name');
            $table->decimal('order_amount', 10, 2);
            $table->string('order_status');
            $table->timestamp('ordered_at');
            $table->timestamp('payment_deadline');
            $table->boolean('is_b2b');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
