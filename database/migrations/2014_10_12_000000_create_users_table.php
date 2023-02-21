<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email', 100)->unique();
            $table->string('password', 250);
            $table->string('email_verify', 100)->nullable();
            $table->string('email_verify_date', 25)->nullable();
            $table->boolean('accountType')->default(0);
            $table->string('companyName', 250)->default('');
            $table->string('companyCIF', 100)->default('');
            $table->boolean('admin')->default(0);
            $table->string('session_token', 50)->nullable();
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
        Schema::dropIfExists('users');
    }
}
