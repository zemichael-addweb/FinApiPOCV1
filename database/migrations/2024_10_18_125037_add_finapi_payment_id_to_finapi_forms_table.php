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
        Schema::table('finapi_forms', function (Blueprint $table) {
            $table->unsignedBigInteger('finapi_payment_id')->nullable()->after('finapi_user_id');
            $table->foreign('finapi_payment_id')->references('id')->on('finapi_payments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finapi_forms', function (Blueprint $table) {
            $table->dropForeign(['finapi_payment_id']);
            $table->dropColumn('finapi_payment_id');
        });
    }
};
