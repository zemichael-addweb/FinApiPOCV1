<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('payment_forms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payment_id');
            $table->string('form_id', 128)->nullable();
            $table->string('form_url', 128)->nullable();
            $table->string('expire_time', 128)->nullable();
            $table->string('type', 128)->nullable();
            $table->string('status', 128)->nullable();
            $table->string('bank_connection_id', 128)->nullable();
            // $table->string('payment_id', 128)->nullable();
            $table->string('standing_order_id', 128)->nullable();
            $table->string('error_code', 128)->nullable();
            $table->string('error_message', 128)->nullable();
            $table->timestamps();

            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_forms');
    }
};
