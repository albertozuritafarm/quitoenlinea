<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles_sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('vehicule_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->string('sales_id')->references('id')->on('sales')->onDelete('cascade');
            $table->string('price');
            $table->string('discount');
            $table->string('picture_front');
            $table->string('picture_back');
            $table->string('picture_right');
            $table->string('picture_left');
            $table->string('picture_roof');
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
        Schema::dropIfExists('vehicles_sales');
    }
}
