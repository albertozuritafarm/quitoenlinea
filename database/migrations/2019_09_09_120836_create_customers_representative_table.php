<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersRepresentativeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers_representative', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('relationship_type_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('document_id');
            $table->string('document');
            $table->string('city_id');
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
        Schema::dropIfExists('customers_representative');
    }
}
