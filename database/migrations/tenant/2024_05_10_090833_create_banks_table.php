<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('trans_date')->nullable();
            $table->string('voucher_no')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('description')->nullable();
            $table->string('debit_amt')->nullable();
            $table->string('credit_amt')->nullable();
            $table->string('voucher_type')->nullable();
            $table->string('ledger_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('instrument_date')->nullable();
            $table->string('transection_type')->nullable();
            $table->string('fav_name')->nullable();
            $table->string('bank_date')->nullable();
            $table->string('credit_ledgers')->nullable();
            $table->string('debit_ledgers')->nullable();
            $table->string('narration')->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banks');
    }
};
