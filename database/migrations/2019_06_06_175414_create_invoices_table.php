<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stores_id');
            $table->unsignedInteger('sales_id');
            $table->unsignedInteger('delivery_orders_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stores_id')->references('id')->on('stores');
            $table->foreign('sales_id')->references('id')->on('users');
            $table->foreign('delivery_orders_id')->references('id')->on('delivery_orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
