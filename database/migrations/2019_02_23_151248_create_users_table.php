<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations. 
     * Create a database table called 'users' 
     * with 'id', 'userable_id', 'userable_type' and 'timestamps' attributes
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userable_id'); 
            $table->string('userable_type'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations. Remove the database table called 'users'
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
