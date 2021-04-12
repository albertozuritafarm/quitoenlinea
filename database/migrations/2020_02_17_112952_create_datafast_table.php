<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatafastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datafast', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_cart');
            $table->string('order');
            $table->string('order_date');
            $table->string('id_transaction');
            $table->string('lot');
            $table->string('reference');
            $table->string('auth_code');
            $table->string('code');
            $table->string('response');
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
        Schema::dropIfExists('datafast');
    }
}
