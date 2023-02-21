<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('userId');
            $table->string('name', 25);
            $table->string('surnames', 100)->nullable();
            $table->text('direction')->nullable();
            $table->string('postalCode', 10)->nullable();
            $table->string('taxes', 100)->default('IGIC');
            $table->tinyInteger('session_expires')->default(0);
            $table->tinyInteger('remember_me')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_settings');
    }
}
