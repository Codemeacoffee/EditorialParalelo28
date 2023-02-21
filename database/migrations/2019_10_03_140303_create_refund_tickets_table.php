<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('shoppingHistoryId');
            $table->text('reason');
            $table->string('status', 50);
            $table->text('statusMessage');
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
        Schema::dropIfExists('refund_tickets');
    }
}
