<?php

namespace App;

class User 
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
     * Fetch the Individual Or Company 
     * 
     */
    public static function fetchUserable($id)
    {
        $user = User::where('id', $id)->first();
        $userable = $user->userable;
        
        return $userable;
    }
}
