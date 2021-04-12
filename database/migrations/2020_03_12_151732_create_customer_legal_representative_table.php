<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerLegalRepresentativeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_legal_representative', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('document');
            $table->string('document_id');
            $table->string('first_name');
            $table->string('second_name');
            $table->string('last_name');
            $table->string('second_last_name');
            $table->string('birth_date');
            $table->string('birth_city');
            $table->string('phone');
            $table->string('mobile_phone');
            $table->string('address');
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
        Schema::dropIfExists('customer_legal_representative');
    }
}
