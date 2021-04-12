<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pbc_id')->references('id')->on('product_by_channel')->onDelete('cascade');
            $table->string('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->dateTime('begin_date');
            $table->dateTime('end_date');
             $table->string('status_id')->references('id')->on('status')->onDelete('cascade');
            $table->string('subtotal');
            $table->string('tax');
            $table->string('total');
            $table->string('tax_id')->references('id')->on('taxes')->onDelete('cascade');
            $table->string('token');
            $table->dateTime('token_date_send');
            $table->dateTime('token_date_validate');
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
        Schema::dropIfExists('sales');
    }
}
