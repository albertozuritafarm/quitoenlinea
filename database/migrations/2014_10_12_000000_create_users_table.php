<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('password');
            $table->string('document');
            $table->string('document_id')->references('id')->on('document')->onDelete('cascade');
            $table->string('role_id')->references('id')->on('role')->onDelete('cascade');
            $table->string('email',128)->unique();
            $table->string('type_id')->references('id')->on('type')->onDelete('cascade');
            $table->string('city_id')->references('id')->on('city')->onDelete('cascade');
            $table->string('agen_id')->references('id')->on('agen')->onDelete('cascade');
            $table->string('status_id')->references('id')->on('status')->onDelete('cascade');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
