<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_order_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('delivery_orders_id');
            $table->unsignedInteger('products_id');
            $table->unsignedInteger('qty');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('delivery_orders_id')->references('id')->on('delivery_orders');
            $table->foreign('products_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_order_products');
    }
}
