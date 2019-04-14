<?php

namespace App;

use Tymon\JWTAuth\Providers\Auth\Illuminate;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    /**
     * The attributes that are editable and assignable.
     *
     * @var array
     */
    protected $fillable = ['userable_type', 'userable_id'];


    /**
     * Get all of the User models. Return either a INDIVIDUAL or COMPANY instance depending on which type of model owns the User.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function userable()
    {
        return $this->morphTo();
    }

    /**
    * Retrieve the timetable for a particular user.
    */
    public function timetable()
    {
        return $this->hasMany('App\Timetable');
    }

    /**
    * Retrieve the appointment for a particular user.
    */
    public function appointment()
    {
        return $this->hasMany('App\Appointment');
    }

    /**
     * Fetch the registration type of the user, either Individual or Company.
     * 
     * @return $user
     */
    public function fetchUserable()
    {
        $user = (new Illuminate(\Auth::Guard('individual')))->user();
        
        if( !isset($user) )
            $user = (new Illuminate(\Auth::Guard('company')))->user();
        
        return $user;
    }

     /**
     * Fetch the registration type of the user, either Individual or Company.
     * 
     * @return $user->user
     */
    public function fetchUser()
    {
        $user = (new Illuminate(\Auth::Guard('individual')))->user();
        
        if( !isset($user) )
            $user = (new Illuminate(\Auth::Guard('company')))->user();
        
        return $user->user;
    }

}
