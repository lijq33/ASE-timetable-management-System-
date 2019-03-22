<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Company::all();

        return response()->json([
            'company' => $company
        ], 200);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        $timetables = $company
            ->user
            ->timetable
            ->where('is_appointment', '1')
            ->where('limited_to', 'public')
            ->all();
        
        foreach($timetables as $timetable) {
            $timetable['StartTime'] = mergeDateTime($timetable->start_time , $timetable->start_date);
            $timetable['EndTime'] = mergeDateTime($timetable->end_time , $timetable->end_date);
        }

        return response()->json([
            'timetables' => $timetables,
            'company' => $company
        ], 200);
    }

}
