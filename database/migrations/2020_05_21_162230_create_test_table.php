<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('country');
            $table->string('province');
            $table->string('city');
            $table->string('document');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('address');
            $table->string('phone');
            $table->string('mobile_phone');
            $table->string('email');
            $table->string('plate');
            $table->string('color');
            $table->string('brand');
            $table->string('model');
            $table->string('year');
            $table->string('begin_date');
            $table->string('end_date');
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
        Schema::dropIfExists('test');
    }
}
