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
        Schema::create('finapi_payments', function (Blueprint $table) {
            $table->id();
            $table->string('finapi_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('finapi_user_id');
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->string('iban')->nullable();
            $table->unsignedBigInteger('bank_id');
            $table->enum('type', ['MONEY_TRANSFER', 'DIRECT_DEBIT']);
            $table->decimal('amount', 10, 2);
            $table->integer('order_count');
            $table->enum('status', ['OPEN', 'PENDING', 'SUCCESSFUL', 'NOT_SUCCESSFUL', 'DISCARDED'])->nullable();
            $table->string('bank_message')->nullable();
            $table->timestamp('request_date')->nullable();
            $table->timestamp('execution_date')->nullable();
            $table->date('instructed_execution_date')->nullable();
            $table->boolean('instant_payment')->default(false);
            $table->enum('status_v2', ['OPEN', 'PENDING', 'SUCCESSFUL', 'NOT_SUCCESSFUL', 'DISCARDED', 'UNKNOWN']);
            $table->string('msg_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('finapi_user_id')->references('id')->on('finapi_users')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('form_id')->references('id')->on('finapi_forms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finapi_payments');
    }
};
