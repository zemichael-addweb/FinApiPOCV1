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
            $table->foreignId('user_id')->nullable()->constrained()->noActionOnDelete();
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
       
        Schema::dropIfExists('orders');
    }
};
