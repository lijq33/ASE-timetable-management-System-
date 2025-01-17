<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndividualsTable extends Migration
{
    /**
     * Run the migrations. 
     * Create a database table called 'individuals' 
     * with 'id', 'name', 'nric', 'email', 'password', 'telephone_number', 'roles' 'token' and 'timestamps' attributes
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individuals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('nric')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('telephone_number');
            $table->string('roles')->default('user');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations. Remove the database table called 'individuals'
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('individuals');
    }
}
