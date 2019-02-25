<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Providers\Auth\Illuminate;

class TimetableController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->all();
        
        //get current user
        // $user_id = (new Illuminate(\Auth::Guard('api')))->user()['id'];
        // $current_user = app('App\User')->userable($user_id);
        
        //Validate Timetable
        $rules = [
            'user_id' => 'bail|required|digits_between:1,100',
            'date' => 'bail|required|date|after:today',
            'time' => 'bail|required',
            'purpose' => 'bail|required|string|max:255',
        ];

        $messages = [
            'unique' => 'This :attribute has been taken!',
            'required' => 'We need to know your :attribute!',
            'max' => 'Your :attribute is too long!',
        ];

        $validator = Validator::make($data = request()->all(), $rules, $messages);

        //if got error return response
        if ($validator->fails()){
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        //store data into database
        Timetable::create([
            'description' => $data['description'],
            'end_time' => $data['end_time'],
            'is_all_day' => $data['is_all_day'],
            'location' => $data['location'],
            'start_time' => $data['start_time'],
            'subject' => $data['subject'],
            'is_appointment' => $data['is_appointment'],
            'date' => $data['date'],
            'interval' => $data['interval'],
        ]);

        if ($data['isAppointment'] === true){

        }

        return response()->json([
            'message' => 'Account is created successfully'
        ], 200);

    }

}
