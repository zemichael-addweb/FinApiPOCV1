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
        Schema::create('finapi_webforms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('finapi_id', 128)->nullable();
            $table->unsignedBigInteger('finapi_user_id')->nullable();;
            $table->unsignedBigInteger('finapi_payment_id')->nullable();
            $table->string('order_ref_number', 128)->nullable();
            $table->string('purpose', 128)->nullable();
            $table->string('form_url', 128)->nullable();
            $table->string('expire_time', 128)->nullable();
            $table->string('type', 128)->nullable();
            $table->string('status', 128)->nullable();
            $table->string('bank_connection_id', 128)->nullable();
            $table->string('standing_order_id', 128)->nullable();
            $table->string('error_code', 128)->nullable();
            $table->string('error_message', 128)->nullable();
            $table->timestamps();

            $table->foreign('finapi_user_id')->references('id')->on('finapi_users')->onDelete('set null');
            $table->foreign('finapi_payment_id')->references('id')->on('finapi_payments')->onDelete('set null');
        });

        Schema::table('finapi_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('finapi_form_id')->nullable()->after('finapi_user_id');
            $table->foreign('finapi_form_id')->references('id')->on('finapi_webforms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finapi_payments', function (Blueprint $table) {
            $table->dropForeign(['finapi_form_id']);
            $table->dropColumn('finapi_form_id');
        });
        Schema::dropIfExists('finapi_webforms');
    }
};
