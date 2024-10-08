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
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('finapi_user_id')->nullable();
            $table->string('order_ref_number', 128)->nullable();
            $table->float('amount')->unsigned();
            $table->string('currency', 16);
            $table->enum('type', ['ORDER', 'DEPOSIT']);
            $table->enum('status', ['OPEN', 'PENDING', 'SUCCESSFUL', 'NOT_SUCCESSFUL', 'DISCARDED', 'UNKNOWN'])->nullable();
            $table->timestamps();

            $table->foreign('finapi_user_id')->references('id')->on('finapi_users')->onDelete('set null');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
