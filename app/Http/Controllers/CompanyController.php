<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Company;

class CompanyController extends Controller
{
    /**
     * This method will retrieve all of the information of companies who registered an account with us.
     *
     * @return \Illuminate\Http\JsonResponse - A list of companies information.
     */
    public function index()
    {
        $company = Company::all();

        return response()->json([
            'company' => $company
        ], 200);
    }
    
    /**
     * The system will retrieve all of the company's public event information and merge the time and date together.
     *
     * @param  Company $company - Contains the company information that the user wants to query on.
     * @return \Illuminate\Http\JsonResponse - All the particular company's public event and the company information.
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
