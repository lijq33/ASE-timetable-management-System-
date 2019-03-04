<?php

namespace App;

use Carbon\Carbon;
use App\Appointment;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['user_id', 'subject', 'start_date', 'end_date', 'start_time', 'end_time','is_all_day',
     'is_appointment', 'limited_to', 'description', 'no_of_slots', 'remaining_slots', 'recurrence_rule', 'location', 'price'];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function appointment(){
        return $this->hasMany(Appointment::class, 'appointment_id');
    }

    /**
     * Determine if timetable has any active Appointment.
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
     * Determine if has Appointment.
     * @return bool
     */
    private function hasAppointment()
    {
        return $this->booking !== null;
    }
    
}
