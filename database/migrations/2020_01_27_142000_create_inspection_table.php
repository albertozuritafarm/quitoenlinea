<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sales_id');
            $table->string('user_id');
            $table->string('status_id');
            $table->string('file');
            $table->string('date_created');
            $table->string('date_status');
            $table->string('date_file');
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
        Schema::dropIfExists('inspection');
    }
}
