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
            $table->string('inv_date')->nullable();
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
            $table->string('qty')->nullable();
            $table->string('uom')->nullable();
            $table->string('item_rate')->nullable();
            $table->string('gst_rate')->nullable();
            $table->string('taxable')->nullable();
            $table->string('narration')->nullable();
            $table->string('sgst')->nullable();
            $table->string('cgst')->nullable();
            $table->string('igst')->nullable();
            $table->string('cess')->nullable();
            $table->string('discount')->nullable();
            $table->string('inv_amt')->nullable();
            $table->string('supplier_invoice_date')->nullable();
            $table->string('original_invoice_no')->nullable();
            $table->string('original_invoice_date')->nullable();
            $table->string('reason_code')->nullable();
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
        Schema::dropIfExists('sale_purchase_invoices');
    }
};
