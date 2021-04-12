<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('main_street');
            $table->string('secondary_street');
            $table->string('number');
            $table->string('office_department');
            $table->string('rubros_cod');
            $table->string('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('properties_rubros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->string('value');
            $table->string('rubro_cod');
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
        Schema::dropIfExists('properties');
        Schema::dropIfExists('properties_rubros');
    }
}
