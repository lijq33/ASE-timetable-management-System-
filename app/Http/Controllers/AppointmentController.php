<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Appointment;
use App\User;

class AppointmentController extends Controller
{
    /**
    * Display a listing of the resource.
    * Retrieve a listing of all appointment that the current user made
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $user = new User();
        $user = $user->fetchUser();

        $appointment = $user->appointment->all();

        return response()->json([
            'appointment' => $appointment,
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
        $data = request()->all();

        //store data into database
        Timetable::create([
            'timetable_id' => $data['timetable_id'],
            'user_id' => $data['user_id'],
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
