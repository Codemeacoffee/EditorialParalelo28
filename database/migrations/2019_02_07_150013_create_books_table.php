<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 250)->unique();
            $table->string('author', 1000);
            $table->text('description')->nullable();
            $table->string('measures', 250)->nullable();
            $table->integer('pages')->nullable();
            $table->string('language', 250)->nullable();
            $table->string('isbn', 500);
            $table->string('bookbinding', 250)->nullable();
            $table->string('edition', 250)->nullable();
            $table->float('physicalPrice');
            $table->float('digitalPrice');
            $table->integer('discount')->nullable();
            $table->integer('stock');
            $table->string('previewImage', 100);
            $table->string('images', 100);
            $table->boolean('promoted') ->default(0);
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
        Schema::dropIfExists('books');
    }
}
