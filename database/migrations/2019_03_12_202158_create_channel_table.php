<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('document');
            $table->string('document_id');
            $table->string('legal_name');
            $table->string('main_road');
            $table->string('secondary_road');
            $table->string('number');
            $table->string('office');
            $table->string('city_id')->references('id')->on('city')->onDelete('cascade');
            $table->string('phone');
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
        Schema::dropIfExists('channel');
    }
}
