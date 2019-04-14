<?php

namespace App;

use Carbon\Carbon;
use App\Appointment;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{

    /**
    * The attributes that are editable and assignable.
    *
    * @var array
    */
    protected $fillable = ['user_id', 'subject', 'start_date', 'end_date', 'start_time', 'end_time','is_all_day',
     'is_appointment', 'limited_to', 'description', 'no_of_slots', 'no_of_appointments', 'recurrence_rule', 'location', 'price'];

    /**
    * Retrieve the appointment for a particular timetable.
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function appointment(){
        return $this->hasMany(Appointment::class);
    }

    /**
     * Determine if the particular timetable has any active Appointment at the moment.
     * @return bool
    */
    public function hasActiveAppointment()
    {
        if (!$this->hasAppointment()) {
            return false;
        }
        return true;
    }

    /**
     * Determine if the particular timetable has Appointment at the moment.
     * @return appointment - if the appointment is not null
     */
    private function hasAppointment()
    {
        return $this->appointment->first() !== null;
    }
    
}
