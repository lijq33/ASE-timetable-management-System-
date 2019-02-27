<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Appointment;
use App\User;

class AppointmentController extends Controller
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

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //Show appointment
    }


    /**
    * Remove the specified resource from storage.
    *
    *  @param Appointment $appointment
    *
    * @return \Illuminate\Http\Response
    */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
       
        return response()->json([
            'message' => 'Appointment is removed'
        ], 200);
    }
}
