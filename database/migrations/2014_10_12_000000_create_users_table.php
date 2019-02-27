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
        Schema::create('designations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('designationTitle')->unique();
            $table->enum('targetArea', ['admin', 'general', 'country', 'state', 'region', 'city', 'block']);
        });

        Schema::create('access_previleges', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('permission', ['allowed', 'denied', 'readonly']);
            $table->string('moduleName');
            $table->unsignedInteger('designationId');
            $table->foreign('designationId')->references('id')->on('designations')->onDelete('cascade'); 
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstName');
            $table->string('lastName')->nullable(true);
            $table->string('profilePic')->nullable(true);
            $table->string('email')->unique();
            $table->string('mobileNumber');
            $table->string('fatherName')->nullable(true);
            $table->string('motherName')->nullable(true);
            $table->date('dateOfBirth')->nullable(true);
            $table->date('dateOfJoin');
            $table->text('presentAddress');
            $table->text('permanentAddress');
            $table->string('drivingLicence')->nullable(true);
            $table->string('panNumber')->nullable(true);
            $table->string('adharNumber')->nullable(true);
            $table->string('passportNumber')->nullable(true);
            $table->enum('maritalStatus', ['married', 'unmarried'])->nullable(true);
            $table->string('currentSalary')->default("0");
            $table->boolean('isActive')->default(true);
            $table->boolean('isAdmin')->default(false);
            $table->string('password');
            $table->unsignedInteger('designationId')->nullable(true);
            $table->foreign('designationId')->references('id')->on('designations')->onDelete('set null'); 
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

        Schema::create('user_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('userId');
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade'); 
            $table->unsignedInteger('countryId')->nullable(true);
            $table->foreign('countryId')->references('id')->on('countries')->onDelete('set null'); 
            $table->unsignedInteger('stateId')->nullable(true);
            $table->foreign('stateId')->references('id')->on('states')->onDelete('set null'); 
            $table->unsignedInteger('regionId')->nullable(true);
            $table->foreign('regionId')->references('id')->on('regions')->onDelete('set null'); 
            $table->unsignedInteger('cityId')->nullable(true);
            $table->foreign('cityId')->references('id')->on('cities')->onDelete('set null'); 
            $table->unsignedInteger('blockId')->nullable(true);
            $table->foreign('blockId')->references('id')->on('blocks')->onDelete('set null'); 
            $table->boolean('isDefault')->default(false);
            $table->unsignedInteger('supervisorId')->nullable(true);
            $table->foreign('supervisorId')->references('id')->on('users')->onDelete('set null'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_areas');
        Schema::dropIfExists('users');
        Schema::dropIfExists('blocks');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('access_previleges');
        Schema::dropIfExists('designations');
    }
}
