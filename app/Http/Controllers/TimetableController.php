<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

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
        //
        // UserID date time "date+time is unique" 
        // purpose description 
        $data = request()->all();
        
        //Fetch the user with $data['user_id']
        $userable = User::fetchUserable($data['user_id']);
        
        var_dump($userable);
        die();
        // For user with $data['user_id'], fetch all appointment at $data['date'], 
        //ensure at $data['time'], they do not have any other appointment
        

        $this->fetchAppointment($data['user_id'], $data['time']);

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
        //Ensure that for every user, they do not have multiple appointment in a single time slot
        $validator = Validator::make($data = request()->all(), $rules, $messages);

     
        //if got error
        if ($validator->fails()){
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        //store data into database
        Timetable::create([
            'user_id' => $data['user_id'],
            'date' => $data['date'],
            'time' => $data['time'],
            'purpose' => $data['purpose'],
            'description' => $data['description'],
        ]);

        return response()->json([
            'message' => 'Account is created successfully'
        ], 200);

    }

}
