<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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

        $timetables = $user->timetable->all();

        //processDateTime
        foreach($timetables as $timetable) {
            $timetable['StartTime'] = mergeDateTime($timetable->start_time , $timetable->start_date);
            $timetable['EndTime'] = mergeDateTime($timetable->end_time , $timetable->end_date);
        }
        
        return response()->json([
            'timetables' => $timetables,
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
        
        $start_datetime = (Carbon::parse($data['start_time']))->addHours(8);
        $end_datetime = (Carbon::parse($data['end_time']))->addHours(8);

        //store data into database
        $timetable = Timetable::create([
            'user_id' => $user_id,
            'subject' => $data['subject'],

            'start_date' => substr($start_datetime, 0, 10),
            'end_date' => substr($end_datetime, 0, 10),
            'start_time' => substr($start_datetime, 11, 8),
            'end_time' => substr($end_datetime, 11, 8),
            'is_all_day' => $data['is_all_day'],        

            'is_appointment' => array_key_exists('is_appointment', $data) ? $data['is_appointment'] : false,
            'limited_to' => array_key_exists('limited_to', $data) ? $data['limited_to'] : null,
            'description' => array_key_exists('description', $data) ? $data['description'] : null,
            'no_of_slots' => array_key_exists('no_of_slots', $data) ? $data['no_of_slots'] : null,
            'no_of_appointments' => array_key_exists('no_of_slots', $data) ? $data['no_of_slots'] : null,
            'recurrence_rule' => array_key_exists('recurrence_rule', $data) ? $data['recurrence_rule'] : null,
            'location' => array_key_exists('location', $data) ? $data['location'] : null,
            'price' => array_key_exists('price', $data) ? substr($data['price'], 1) : 0,
        ]);
        
        return response()->json([
            'message' => 'Timetable is created successfully',
            'id' => $timetable->id
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
        //if appointment exist, dont allow update
        if ($timetable->hasActiveAppointment()) {
            return response()->json([
                'message' => 'There are appointment in place!'
            ], 422);
        }

        $data = request()->all();
        
        //processDateTime
        $start_time = (Carbon::parse(substr($data['start_time'], 11, 8)))->addHours(8);
        $end_time = (Carbon::parse(substr($data['end_time'], 11, 8)))->addHours(8);

        if(array_key_exists('no_of_slots', $data)){
            if($data['no_of_slots'] < $timetable['no_of_appointments']){
                return response()->json([
                    'message' => "You can't reduce the number of slots to be below" + $timetable['no_of_appointments'] ,
                ], 402);
            }
        }

        $timetable->update([
            'subject' => $data['subject'],
            'start_date' => substr($data['start_time'], 0, 10),
            'end_date' => substr($data['end_time'], 0, 10),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'is_all_day' => $data['is_all_day'],        

            'is_appointment' => array_key_exists('is_appointment', $data) ? $data['is_appointment'] : false,
            'limited_to' => array_key_exists('limited_to', $data) ? $data['limited_to'] : null,
            'description' => array_key_exists('description', $data) ? $data['description'] : null,
            'no_of_slots' => array_key_exists('no_of_slots', $data) ? $data['no_of_slots'] : null,
            'recurrence_rule' => array_key_exists('recurrence_rule', $data) ? $data['recurrence_rule'] : null,
            'location' => array_key_exists('location', $data) ? $data['location'] : null,
            'price' => array_key_exists('price', $data) ? substr($data['price'], 1) : 0,
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

        $timetable->delete();
       
        return response()->json([
            'message' => 'Timetable is removed'
        ], 200);
    }

}
