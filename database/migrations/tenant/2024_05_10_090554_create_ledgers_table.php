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
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('party_name')->nullable();
            $table->string('alias')->nullable();
            $table->string('group_name')->nullable();
            $table->date('credit_period')->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('gst_in')->nullable();
            $table->string('gst_reg_type')->nullable();
            $table->decimal('opening_balance')->nullable();
            $table->date('applicable_date')->nullable();
            $table->string('tags')->nullable();
            $table->string('tally_guid')->nullable();
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
        Schema::dropIfExists('ledgers');
    }
};
