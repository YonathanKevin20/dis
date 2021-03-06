<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_delivery_order')->unique();
            $table->unsignedInteger('spvs_id');
            $table->unsignedInteger('sales_id');
            $table->unsignedInteger('vehicles_id');
            $table->enum('status', ['0', '1', '2'])->default('0');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('spvs_id')->references('id')->on('users');
            $table->foreign('sales_id')->references('id')->on('users');
            $table->foreign('vehicles_id')->references('id')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_orders');
    }
}
