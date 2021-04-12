<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatafastLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datafast_conection_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('request');
            $table->string('request_date');
            $table->string('response');
            $table->string('response_date');
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
        Schema::dropIfExists('datafast_conection_log');
    }
}
