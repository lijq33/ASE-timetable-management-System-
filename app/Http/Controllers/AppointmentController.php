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
    * Retrieve a listing of all appointment that the current user has made.
    * After retrieving, merge the date and time together and add it to the back of the array $timetables.
    * 
    * @return \Illuminate\Http\JsonResponse - An array that contains all of the appointment information.
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
     * Store a newly created appointment into the database. 
     * First, retrieve the user and his/her timetable in order to get the user current appointment.
     * If the current appointment is not null or the current timeslot doesnt have enough slot allocated for the new appointment, a message will be displayed.
     * Else, the appointment will be stored inside the database and increase the no_of_appointments by 1 to indicate that one of the timeslot is taken.
     * After which, Stripe will be set up and user can make payment through Stripe.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse - Message to indicate that the appointment is successfully booked.
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
    * Remove a specified appointment from the database and reduce the no_of_appointments by 1.
    *
    * @param $timetable_id - To easily retrieve the timetable of the current user.
    * @return \Illuminate\Http\JsonResponse - Message to indicate that the appointment has been successfully removed.
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
