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
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->string('subject');

            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->tinyInteger('is_all_day');

            $table->tinyInteger('is_appointment');

            $table->string('limited_to')->nullable(); //public private invites
            $table->string('description')->nullable();
            $table->integer('no_of_slots')->nullable();
            $table->integer('remaining_slots')->nullable();
            $table->string('recurrence_rule')->nullable();
            
            $table->string('location')->nullable();

            $table->double('price');
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
