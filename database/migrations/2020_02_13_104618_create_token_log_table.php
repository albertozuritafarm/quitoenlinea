<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTokenLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('token_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('send_data');
            $table->string('send_date');
            $table->string('response_data');
            $table->string('response_date');
            $table->string('response_code');
            $table->string('type');
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
        Schema::dropIfExists('token_log');
    }
}
