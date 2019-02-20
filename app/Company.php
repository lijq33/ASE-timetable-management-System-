<?php

namespace App;

use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class Company extends User
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [            
        'company_name',
        'company_type',
        'industry_type',
        'website',
        'tagline',
        'logo',
        'email',
        'password',
        'telephone_number',
    ];

    /**
     * @param $data
     * 
     * Create an individual account based on $data
     * 
     */
    public function createAccount($data)
    {
        $rules = [
            'company_name' => 'bail|required|string|max:255',
            'company_type' => 'bail|required|string|max:255',
            'industry_type' => 'bail|required|string|max:255',
            'email' => 'bail|required|string|email|max:255|unique:companies',
            'password' => 'bail|required|string|min:6|confirmed|max:30',
            'telephone_number' => 'bail|required|digits:8|unique:companies',
        ];
    
        $messages = [
            'unique' => 'This :attribute has been taken!',
            'required' => 'We need to know your :attribute!',
            'email' => 'The format of :attribute is incorrect!',
            'max' => 'Your :attribute is too long!',
            'max' => 'Your :attribute is too short!',
        ];
    
        $validator = Validator::make($data = request()->all(), $rules, $messages);

        if ($validator->fails()) {
            return $validator;
        }

        $imageName = null;

        if ($data['logo'] !== "null"){        
            $imageName= str_random(40);
            $image = Image::make($data['logo']->getRealPath());
            $image->save(public_path('logos\\') .  $imageName . ".{$data['logo']->getClientOriginalExtension()}"); // Original Image
            $imageName = $imageName.".".$data['logo']->getClientOriginalExtension();
        }
        Company::create([
            'company_name' => $data['company_name'],
            'company_type' => $data['company_type'],
            'industry_type' => $data['industry_type'],

            'website' => $data['website'],
            'tagline' => $data['tagline'],
            'logo' => $imageName,

            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'telephone_number' => $data['telephone_number'],
        ]);
        
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

