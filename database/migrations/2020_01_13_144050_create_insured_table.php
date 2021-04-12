<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsuredTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insured', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('document_id');
            $table->string('document');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address');
            $table->string('city_id');
            $table->string('phone');
            $table->string('mobile_phone');
            $table->string('email');
            $table->string('status_id');
            $table->string('birth_date');
            $table->string('nacionality_id');
            $table->string('gender_id');
            $table->string('civil_status_id');
            $table->string('profession');
            $table->string('activity');
            $table->string('work_address');
            $table->string('correspondence_id');
            $table->string('second_name');
            $table->string('second_last_name');
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
        Schema::dropIfExists('insured');
    }
}
