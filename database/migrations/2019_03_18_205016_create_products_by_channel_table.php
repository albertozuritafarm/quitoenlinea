<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsByChannelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_by_channel', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('channel_id')->references('id')->on('channels')->onDelete('cascade');
            $table->string('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->dateTime('begin_date');
            $table->dateTime('end_date');
            $table->string('status_id')->references('id')->on('status')->onDelete('cascade');
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
        Schema::dropIfExists('products_by_channel');
    }
}
