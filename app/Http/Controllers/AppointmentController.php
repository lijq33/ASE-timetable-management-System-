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

// timetable_id
// start_time
// end_time



        $validator = validator($data = request()->all(), OnlineClassBooking::$rules, OnlineClassBooking::$messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $onlineClass = OnlineTuitionClass::find($data['class_id']);

        $startDate = $onlineClass->getStartDate();

        //for url access
        $data['token'] = str_random(40);

        $onlineClassBooking = OnlineClassBooking::create([
            'token'                   => $data['token'],
            'name'                    => $data['name'],
            'email'                   => $data['email'],
            'telephone_number'        => $data['telephone_number'],
            'start_date'              => $startDate,
            'ip_address'              => getIp(),
            'online_tuition_class_id' => $data['class_id'],
        ]);

        $tuitionClass = $onlineClassBooking->onlineTuitionClass;

        $tuitionClass->update([
            'sign_up_count'      => $tuitionClass->sign_up_count + 1,
            'current_class_size' => $tuitionClass->current_class_size + 1,
        ]);

        // set up stripe key and make payment
        Stripe::setApiKey(config('services.stripe.secret'));

        $customer = Customer::create([
            'source' => $data['stripeToken'],
        ]);

        //because price is in cents
        $price = OnlineTuitionClass::find($data['class_id'])->first()->price;
        $price *= 100;

        Charge::create([
            'customer' => $customer->id,
            'amount'   => $price,
            'currency' => 'sgd',
            'metadata' => [
                'phone'           => $data['telephone_number'],
                'email'           => $data['email'],
                'online_class_id' => $data['class_id'],
                'start_date'      => $startDate,
            ],
        ]);

        Email::acknowledgeTutorOnlineClassSignupSuccess($onlineClassBooking);
        Email::acknowledgeStudentOnlineClassSignupSuccess($onlineClassBooking);

        return response()->json([
            'url'     => url("/online_class_bookings/verify"),
            'message' => null,
        ]);




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
