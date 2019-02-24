<?php

namespace App;

use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Facades\Validator;

class Individual extends User
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'nric', 'telephone_number'
    ];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\MorphOne
    */
    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    /**
     * @param $data
     * 
     * Create an individual account based on $data
     * 
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
