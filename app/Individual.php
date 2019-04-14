<?php

namespace App;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\User;

class Individual extends Authenticatable implements JWTSubject
{
    use Notifiable;
    
    /**
     * The attributes that are editable and assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'nric', 'telephone_number'
    ];

    /**
    * Setting up one is to many relation between Individual and Timetable.
    *
    * @return \Illuminate\Database\Eloquent\Relations\MorphOne
    */
    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
    * Get the timetable for the particular individual. Setting up one is to many relation between Individual and Timetable.
    *
    * @return timetables
    */
    public function timetable()
    {
        return $this->hasMany('App\Timetable');
    }

    /**
     * Create an individual account based on $data. $rules contains all the correct format we are looking for when users are doing their registration.
     * After validating their inputs, we will be creating the user and storing it into our database. 
     * Else, we will return the appropriate message so that the user knows which input is incorrect.
     * 
     * @param $data
     * @return $validator - if one of the inputs is in incorrect format
     */
    public function createAccount($data)
    {
        $rules = [
            'nric' => array(
                        'bail', 
                        'required',
                        'unique:individuals',
                        'size:9',
                        'regex:/[a-zA-Z]\d{7}[a-zA-Z]/u',
                        'string',
                    ),
            'email' => 'bail|required|string|email|max:255|unique:individuals',
            'name' => 'bail|required|string|max:255',
            'password' => 'bail|required|string|min:6|confirmed|max:30',
            'telephone_number' => 'bail|required|digits:8',
        ];

        $messages = [
            'unique' => 'This :attribute has been taken!',
            'required' => 'We need to know your :attribute!',
            'email' => 'The format of :attribute is incorrect!',
            'max' => 'Your :attribute is too long!',
            'digits:value' => 'Your :attribute should have :value digits',
            'regex' => 'The format of :attribute is wrong'
        ];
    
        $validator = Validator::make($data = request()->all(), $rules, $messages);

        if ($validator->fails()) {
            return $validator;
        }

        $individual = Individual::create([
            'name' => $data['name'],
            'nric' => $data['nric'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'telephone_number' => $data['telephone_number'],
        ]);
        
        $user = new User(['userable_id' => $individual->id, 'userable_type' => Individual::class]);

        $individual->user()->save($user);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

}
