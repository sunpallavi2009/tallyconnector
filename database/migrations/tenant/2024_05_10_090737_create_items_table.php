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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name')->nullable();
            $table->string('uom')->nullable();
            $table->string('alias1')->nullable();
            $table->string('alias2')->nullable();
            $table->string('part_no')->nullable();
            $table->string('item_desc')->nullable();
            $table->string('hsn_code')->nullable();
            $table->string('hsn_desc')->nullable();
            $table->string('taxability')->nullable();
            $table->decimal('gst_rate')->nullable();
            $table->date('applicable_from')->nullable();
            $table->decimal('cgst_rate')->nullable();
            $table->decimal('sgst_rate')->nullable();
            $table->decimal('igst_rate')->nullable();
            $table->decimal('opening_qty')->nullable();
            $table->decimal('rate')->nullable();
            $table->decimal('amount')->nullable();
            $table->string('gst_type_of_supply')->nullable();
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
        Schema::dropIfExists('items');
    }
};
