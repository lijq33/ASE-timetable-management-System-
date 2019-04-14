<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use app\HospitalAppointment;

class UserFactory extends Model
{
    /*
    * Build an User based on the provided information
    *
    * @param $type - contains all the information to create an user 
    * @return $user or \Illuminate\Http\JsonResponse (display of failure message)
    */
    public static function build($type) {
        $user = "\App\\" . $type;
        if (class_exists($user)) {
            return new $user();
        }
        else 
        {
            return response()->json([
                'error' => 'Type selected does not exist'
            ], 422);
        }
    } 
}