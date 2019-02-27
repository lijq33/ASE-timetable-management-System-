<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users');

            $table->tinyInteger('is_appointment');
            $table->tinyInteger('limited_to'); //public private invites
            
            $table->string('subject');
            $table->string('description');
            
            $table->string('location');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            
            $table->tinyInteger('is_all_day');
            $table->tinyInteger('require_payment');
            $table->tinyInteger('price');
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
        Schema::dropIfExists('timetables');
    }
}
