<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTaxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_tax', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tax_id',20)->references('id')->on('channels')->onDelete('cascade');
            $table->string('sale_id',20)->references('id')->on('channels')->onDelete('cascade');
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
        Schema::dropIfExists('sales_tax');
    }
}
