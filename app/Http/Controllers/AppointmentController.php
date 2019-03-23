<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Stripe\{Stripe, Charge, Customer};

use App\Appointment;
use App\Timetable;
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
            $timetable = $appointment->timetable;
            $timetable['StartTime'] = mergeDateTime($timetable->start_time , $timetable->start_date);
            $timetable['EndTime'] = mergeDateTime($timetable->end_time , $timetable->end_date);
            array_push($timetables, $timetable);
        }

        return response()->json([
            'appointments' => $timetables,
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

        $timetable = Timetable::find($data['timetable_id']);
        
        $userable = User::find($timetable->user_id);
        $company = $userable->userable;
        
        $user = new User();
        $user = $user->fetchUser();

        $individual = $user->fetchUserable();

        $cur_appointment = Appointment::where('timetable_id', $data['timetable_id'])
                                        ->where('user_id', $user->user_id)
                                        ->first();

        if($cur_appointment != null)
            return response()->json([
                'message' => "You have already secure this appointment!",
            ], 422);

        if ($timetable->no_of_slots != null){
            if ($timetable->no_of_slots <= $timetable->no_of_appointments)
                return response()->json([
                    'message' => "Booking is not possible as there are no more slots remaining",
                ], 422);
        }

        //store data into database
        Appointment::create([
            'timetable_id' => $data['timetable_id'],
            'user_id' => $user->id,
            
            'start_date' => $timetable->start_date,
            'end_date' => $timetable->end_date,
            'start_time' => $timetable->start_time,
            'end_time' => $timetable->end_time,
        ]);

        
        $timetable->update([
            'no_of_appointments' => $timetable->no_of_appointments + 1,
        ]);

        if ($timetable->price == 0)
            return response()->json([
                'message' => 'Appointment has been successfully booked !'
            ], 200);
        

        // set up stripe key and make payment
        Stripe::setApiKey(config('services.stripe.secret'));

        $customer = Customer::create([
            'source' => $data['stripeToken'],
        ]);

        //because price is in cents
        $price = $timetable->price;
        $price *= 100;

        Charge::create([
            'customer' => $customer->id,
            'amount'   => $price,
            'currency' => 'sgd',
            'metadata' => [
                
                //Company Info
                'company_name'  => $company->company_name,
                'company_email' => $company->email,
                'company_phone' => $company->telephone_number,

                //User Info
                'user_name'     => $individual->name,
                'user_email'    => $individual->email,
                'user_phone'    => $individual->telephone_number,

                //Booking Info
                'timetable_id'  => $data['timetable_id'],
                'start_date'    => $timetable->start_date,
                'end_date'      => $timetable->end_date,
                'start_time'    => $timetable->start_time,
                'end_time'      => $timetable->end_time,
            ]
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
    public function destroy($timetable_id)
    {
        
        $user = new User();
        $user = $user->fetchUser();


        $timetable = Timetable::find($timetable_id)->first();
        
        $timetable->update([
            'no_of_appointments' => $timetable->no_of_appointments - 1,
        ]);

        $appointment = Appointment::where('timetable_id', $timetable_id)
                                    ->where('user_id', $user->id)
                                    ->first();
        $appointment->delete();
       
        return response()->json([
            'message' => 'Appointment is removed'
        ], 200);
    }
}
