<?php

namespace App;

use App\Timetable;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = ['timetable_id', 'user_id' ];

    public function timeTable()
    {
        return $this->belongsTo(Timetable::class, 'timetable_id');
    }
}
