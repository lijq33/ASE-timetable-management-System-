<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Appointment;
use App\User;
use App\Company;

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

        $appointments = $user->appointment->all();

        $timetables = [];

        foreach($appointments as $appointment){
            $timetables += $appointment->timetable;
        }

        return response()->json([
            'appointment' => $timetables,
        ], 200);
    }


    /**
    * Display a specific resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function retrieveAppointment($id)
    {

        //timetable
        // $timetables = Company::find($id)
        //             ->user
        //             ->timetable
        //             ->where('is_appointment', '1')
        //             ->where('limited_to', 'public')
        //             ->pluck('id');

        // $user = new User();
        // $user_id = $user->fetchUser()->id;

        // var_dump($timetables);

        // $appointments = [];
        // foreach($timetables as $timetable){
        //     $appointments += Appointment::where('user_id', $user_id)
        //                 ->where('timetable_id', $timetable)
        //                 ->get();
        // }

        // return response()->json([
        //     // 'appointment' => $appointments,
        //     'appointments' => $appointments,
        //     // 'user' => $user,
        // ], 200);
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

        if ($data['isAppointment'] === false){   
            return response()->json([
                'message' => 'Invalid request'
            ], 401);
        }

        //store data into database
        Appointment::create([
            'timetable_id' => $data['timetable_id'],
            'user_id' => $data['user_id'],
        ]);

        return response()->json([
            'message' => 'Appointment has been successfully booked !'
        ], 200);
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
