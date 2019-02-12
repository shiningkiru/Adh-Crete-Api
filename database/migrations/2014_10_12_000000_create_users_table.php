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
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('countryName')->unique();
            $table->string('countryCode')->unique();
        });

        Schema::create('states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stateName')->unique();
            $table->unsignedInteger('countryId');
            $table->foreign('countryId')->references('id')->on('countries')->onDelete('cascade'); 
        });
        
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('regionName')->unique();
            $table->unsignedInteger('stateId');
            $table->foreign('stateId')->references('id')->on('states')->onDelete('cascade'); 
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cityName')->unique();
            $table->unsignedInteger('regionId');
            $table->foreign('regionId')->references('id')->on('regions')->onDelete('cascade'); 
        });

        Schema::create('blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('blockName')->unique();
            $table->unsignedInteger('cityId');
            $table->foreign('cityId')->references('id')->on('cities')->onDelete('cascade'); 
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
        Schema::dropIfExists('blocks');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
    }
}
