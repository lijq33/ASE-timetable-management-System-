<?php

namespace App;

use App\Timetable;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{

    /**
     * The attributes that are editable and assignable.
     *
     * @var array
    */
    protected $fillable = ['timetable_id', 'user_id', 'start_date', 'end_date', 'start_time', 'end_time'];

    /**
     * Fetch the particular timetable's id that the appointment is stored at.
     *
     * @return timetable_id
    */
    public function timetable()
    {
        return $this->belongsTo(Timetable::class, 'timetable_id');
    }
}
