<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     * Create a database table called 'appointments'
     * with 'id', 'timetable_id', 'user_id', 'start_date', 'end_date', 'start_time', 'end_time', 'timestamps' attributes
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints(); 
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('timetable_id')->unsigned();
            $table->foreign('timetable_id')
                    ->references('id')->on('timetables');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                    ->references('id')->on('users');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations. Remove the database table called 'appointments'
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
