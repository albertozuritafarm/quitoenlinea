<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('document');
            $table->string('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->string('business_name');
            $table->string('economic_activity_id')->references('id')->on('economic_activity')->onDelete('cascade');
            $table->date('constitution_date');
            $table->string('address');
            $table->string('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->string('cross_street');
            $table->string('address_number');
            $table->string('parroquia');
            $table->string('sector');
            $table->string('phone');
            $table->string('mobile_phone');
            $table->string('email');
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
        Schema::dropIfExists('companys');
    }
}
