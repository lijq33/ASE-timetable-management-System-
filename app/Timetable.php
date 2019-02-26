<?php

namespace App;

use App\Appointment;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['company_name', 'company_type', 'industry_type', 'website', 'tagline', 'logo', 'email', 'password', 'telephone_number'];


    /**
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function appointment(){
        return $this->hasMany(Appointment::class, 'appointment_id');
    }

    public function getTimeToAttribute($time)
    {
        return Carbon::parse($time);
    }

    public function getTimeFromAttribute($time)
    {
        return Carbon::parse($time);
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
        return $this->booking->first() !== null;
    }
    
}
