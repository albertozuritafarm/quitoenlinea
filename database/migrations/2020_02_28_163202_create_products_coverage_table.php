<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsCoverageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_coverage', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('products_id');
            $table->string('coverage_id');
            $table->string('value');
            $table->string('type');
            $table->string('coverage_type_id');
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
        Schema::dropIfExists('products_coverage');
    }
}
