<?php

namespace App;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\User;

class Company extends Authenticatable implements JWTSubject
{

    use Notifiable;
    /**
     * The attributes that are editable and assignable.
     *
     * @var array
     */
    protected $fillable = ['company_name', 'company_type', 'industry_type', 'website', 'tagline', 'logo', 'email', 'password', 'telephone_number'];

    /**
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

    // /**
    // * Get the timetable for the company.
    // */
    // public function timetable()
    // {
    //     return $this->hasMany('App\Timetable');
    // }

    /**
     * Create a Company account based on $data. $rules contains all the correct format we are looking for when companies are doing their registration.
     * After validating their inputs, we will be creating the Company account and storing it into our database. 
     * Else, we will return the appropriate message so that the company knows which input is incorrect.
     * 
     * @param $data
     * @return $validator - if one of the inputs is in incorrect format
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

        $imageName = "defaultCompany.jpg";

        if ($data['logo'] !== "null"){        
            $imageName = str_random(40);
            $image = Image::make($data['logo']->getRealPath());
            $image->save(public_path('logos\\') .  $imageName . ".{$data['logo']->getClientOriginalExtension()}"); // Original Image
            $imageName = $imageName.".".$data['logo']->getClientOriginalExtension();
        }

        $company = Company::create([
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

        $user = new User(['userable_id' => $company->id, 'userable_type' => Company::class]);

        $company->user()->save($user);
        
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

