<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVinculationFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vinculation_form', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer_id')->nullable($value = true);
            $table->string('birth_place')->nullable($value = true);
            $table->string('birth_date')->nullable($value = true);
            $table->string('nationality_id')->nullable($value = true);
            $table->string('city_id')->nullable($value = true);
            $table->string('address_zone')->nullable($value = true);
            $table->string('main_road')->nullable($value = true);
            $table->string('secondary_road')->nullable($value = true);
            $table->string('address_number')->nullable($value = true);
            $table->string('email')->nullable($value = true);
            $table->string('secondary_email')->nullable($value = true);
            $table->string('mobile_phone')->nullable($value = true);
            $table->string('phone')->nullable($value = true);
            $table->string('civil_state')->nullable($value = true);
            $table->string('passport_number')->nullable($value = true);
            $table->string('begin_date')->nullable($value = true);
            $table->string('end_date')->nullable($value = true);
            $table->string('migration_status_id')->nullable($value = true);
            $table->string('entry_date')->nullable($value = true);
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
        Schema::dropIfExists('vinculation_form');
    }
}
