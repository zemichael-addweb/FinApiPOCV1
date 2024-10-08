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
        Schema::create('finapi_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('endpoint');
            $table->json('headers');
            $table->json('payload');
            $table->string('response_code', 128);
            $table->json('response_body');
            $table->string('request_id', 128);
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finapi_requests');
    }
};
