<?php

namespace App;

use Tymon\JWTAuth\Providers\Auth\Illuminate;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['userable_type', 'userable_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function userable()
    {
        return $this->morphTo();
    }

    /**
    * Get the timetable for the user.
    */
    public function timetable()
    {
        return $this->hasMany('App\Timetable');
    }

    /**
    * Get the appointment for the user.
    */
    public function appointment()
    {
        return $this->hasMany('App\Appointment');
    }

    /**
     * Fetch the Individual Or Company 
     * 
     */
    public function fetchUserable()
    {
        $user = (new Illuminate(\Auth::Guard('individual')))->user();
        
        if( !isset($user) )
            $user = (new Illuminate(\Auth::Guard('company')))->user();
        
        return $user;
    }

     /**
     * Fetch the Individual Or Company 
     * 
     */
    public function fetchUser()
    {
        $user = (new Illuminate(\Auth::Guard('individual')))->user();
        
        if( !isset($user) )
            $user = (new Illuminate(\Auth::Guard('company')))->user();
        
        return $user->user;
    }

}
