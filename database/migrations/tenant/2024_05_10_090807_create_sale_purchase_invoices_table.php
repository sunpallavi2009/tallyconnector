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
        Schema::create('sale_purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->date('inv_date')->nullable();
            $table->string('inv_no')->nullable();
            $table->string('bill_ref_no')->nullable();
            $table->string('voucher_type')->nullable();
            $table->string('party_name')->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('gst_in')->nullable();
            $table->string('gst_reg_type')->nullable();
            $table->string('place_of_supply')->nullable();
            $table->string('company_reg_type')->nullable();
            $table->string('item_name')->nullable();
            $table->string('item_desc')->nullable();
            $table->decimal('qty')->nullable();
            $table->string('uom')->nullable();
            $table->decimal('item_rate')->nullable();
            $table->decimal('gst_rate')->nullable();
            $table->decimal('taxable')->nullable();
            $table->string('narration')->nullable();
            $table->decimal('sgst')->nullable();
            $table->decimal('cgst')->nullable();
            $table->decimal('igst')->nullable();
            $table->decimal('cess')->nullable();
            $table->decimal('discount')->nullable();
            $table->decimal('inv_amt')->nullable();
            $table->date('supplier_invoice_date')->nullable();
            $table->string('original_invoice_no')->nullable();
            $table->date('original_invoice_date')->nullable();
            $table->string('reason_code')->nullable();
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
        Schema::dropIfExists('sale_purchase_invoices');
    }
};
