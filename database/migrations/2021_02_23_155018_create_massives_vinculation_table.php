<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMassivesVinculationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('massives_vinculation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->string('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('branch');
            $table->string('emission_type_id')->references('id')->on('emission_type')->onDelete('cascade');
            $table->string('insured_value');
            $table->string('net_premium');
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
        Schema::dropIfExists('massives_vinculation');
    }
}
