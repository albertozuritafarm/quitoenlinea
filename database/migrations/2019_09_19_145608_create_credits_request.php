<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_customer');
            $table->string('id_bank');
            $table->string('amount');
            $table->string('financing');
            $table->string('number');
            $table->string('date');
            $table->string('id_product');
            $table->string('status_id');
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
        Schema::dropIfExists('credits_request');
    }
}
