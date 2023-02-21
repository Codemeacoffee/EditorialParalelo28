<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoppingHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('userId');
            $table->bigInteger('shipmentCode');
            $table->float('price');
            $table->string('status', 50);
            $table->string('details', 250);
            $table->bigInteger('authorisationCode');
            $table->string('shipmentName', 250);
            $table->string('shipmentSurnames', 250);
            $table->string('shipmentAddress', 250);
            $table->string('shipmentPostCode', 250);
            $table->string('billingName', 250);
            $table->string('billingSurnames', 250);
            $table->string('billingAddress', 250);
            $table->string('billingPostCode', 250);
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
        Schema::dropIfExists('shopping_history');
    }
}
