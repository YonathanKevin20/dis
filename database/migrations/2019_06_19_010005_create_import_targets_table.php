<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_targets', function (Blueprint $table) {
            $table->increments('id');
            $table->char('year', 4);
            $table->char('month', 2);
            $table->unsignedInteger('products_id')->nullable();
            $table->unsignedInteger('qty');

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
        Schema::dropIfExists('import_targets');
    }
}
