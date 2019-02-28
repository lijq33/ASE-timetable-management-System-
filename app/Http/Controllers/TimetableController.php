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
    * Display a specific resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function show(Timetable $timetable)
    {
        if ($timetable->is_appointment == false) 
            return response()->json([
                'message' => 'Selected timeslot is not an appointment.'
            ], 200);

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
            
        //store data into database
        Timetable::create([
            'id' => $data['id'],
            'user_id' => $user_id,
            
            'is_appointment' => $data['is_appointment'],
            'event_type' => $data['limited_to'],

            'subject' => $data['subject'],
            'description' => $data['description'],

            'location' => $data['location'],
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            
            'is_all_day' => $data['is_all_day'],        
            'require_payment' => $data['require_payment'],
            'price' => $data['price'],
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
            'event_type' => $data['event_type'],      
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
