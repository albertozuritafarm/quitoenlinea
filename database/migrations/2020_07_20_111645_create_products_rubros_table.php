<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsRubrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_rubros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('products_id');
            $table->string('cod');
            $table->string('description');
            $table->string('indicator');
            $table->string('max_value');
            $table->string('min_value');
            $table->string('value');
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
        Schema::dropIfExists('products_rubros');
    }
}