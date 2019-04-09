<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\User;
use Illuminate\Http\Request;
use App\Timetable;

class TimetableController extends Controller
{
    /**
    * Retrieve all the timetable events that belongs to a particular user.
    * After retrieving the events from database, the time and date will be merged before returning to the system.
    * @return \Illuminate\Http\JsonResponse - All of the user's events stored in the database.
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
    * Display a specific timeslot event.
    * The system will first check if the selected timetable event is an appointment.
    * If yes, the timeslot event information will be displayed.
    * Else, a message, stating it is not an appointment, will be displayed.
    * @param Timetable $timetable - The selected timeslot event information.
    * @return \Illuminate\Http\JsonResponse - The particular timeslot event details.
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
     * Store a newly created event in database.
     * First, the timezone of the event will be checked first to ensure that it is from the same timezone as the system. 
     * If it is not from GMT +8, 8 hours will be added to match our timezone.
     * After which the newly created event will be stored into our database.
     * A successful message will be displayed after the storing is completed.
     * @param  \Illuminate\Http\Request $request - The newly created event information.
     * @return \Illuminate\Http\JsonResponse - Display of successful message and the id of the newly stored timetable event.
     */
    public function store(Request $request)
    {
        $user = new User();
        $user_id = $user->fetchUser()->id;
        
        $data = request()->all();

        if(substr($data['start_time'], 19, 3) !== '+08'){
            $start_datetime = (Carbon::parse($data['start_time']))->addHours(8);
            $end_datetime = (Carbon::parse($data['end_time']))->addHours(8);
        }else{
            $start_datetime = (Carbon::parse($data['start_time']));
            $end_datetime = (Carbon::parse($data['end_time']));
        }

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
     * Update a particular event and store the new data into our database.
     * The system will check if there is any existing event in that timeslot. If yes, no update will be performed.
     * The system will ensure that the updated event will take up the expected number of timeslot.
     * After which the newly updated event information will replace the old event stored inside our database.
     * A successful message will be displayed after the update has been completed.
     *
     * @param  \Illuminate\Http\Request $request - Contains all the information of the updated event.
     * @param  Timetable $timetable - Contains all the timetable information.
     * @return \Illuminate\Http\JsonResponse - Display of successful message.
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
    * Remove the timetable from the database.
    * The system will check if there is any active appointment in the timetable. If yes, deletion will not be allowed.
    * Else, the system will proceed with deleting the timetable from the database.
    * A successful message will be displayed after the deletion has been completed.
    * @param Timetable $timetable - Contains all the timetable information
    * @return \Illuminate\Http\JsonResponse - Display of successful message.
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
