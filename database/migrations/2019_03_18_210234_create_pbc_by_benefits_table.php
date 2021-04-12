<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePbcByBenefitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pbc_by_benefits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pbc_id')->references('id')->on('product_by_channel')->onDelete('cascade');
            $table->string('benefits_id')->references('id')->on('benefits')->onDelete('cascade');
            $table->string('status_id')->references('id')->on('status')->onDelete('cascade');
            $table->dateTime('begin_date');
            $table->dateTime('end_date');
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
        Schema::dropIfExists('pbc_by_benefits');
    }
}
