<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Timetable;

class TimetableController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $user = new User();
        $user = $user->fetchUser();

        $timetable = $user->timetable->all();

        return response()->json([
            'timetable' => $timetable,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        $user_id = $user->fetchUser()->id;
        
        $data = request()->all();
  
        //perform validation


        // end_time
        // event_type
        // is_all_day
        // Location
        // RecurrenceRule
        // start_time
        // subject:
        // is_appointment
        // description

    

        //store data into database
        Timetable::create([
            'user_id' => $user_id,
            'description' => $data['description'],
            'end_time' => $data['end_time'],
            'is_all_day' => $data['is_all_day'],
            'location' => $data['location'],
            'start_time' => $data['start_time'],
            'subject' => $data['subject'],
            'is_appointment' => $data['is_appointment'],
            'date' => $data['date'],
        ]);

        return response()->json([
            'message' => 'Account is created successfully'
        ], 200);

    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Timetable $timetable)
    {
        //retrieve all appointment
        $timetable->appointment();

        //if appointment exist, dont allow update
        if ($timetable->hasActiveAppointment()) {
            return response()->json([
                'message' => 'There are appointment in place!'
            ], 422);
        }

        $data = request()->all();

        //perform validation

        $timetable->update([
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
       
        return response()->json([
            'message' => 'Timetable has been successfully updated'
        ], 200);
    }


    /**
    * Remove the specified resource from storage.
    *
    *  @param Timetable $timetable
    *
    * @return \Illuminate\Http\Response
    */
    public function destroy(Timetable $timetable)
    {
        if ($timetable->hasActiveAppointment()) {
            return response()->json([
                'message' => 'There are appointment in place!'
            ], 422);
        }

        $timetable->appointment()->delete();

        $timetable->delete();
       
        return response()->json([
            'message' => 'Timetable is removed'
        ], 200);
    }

}
