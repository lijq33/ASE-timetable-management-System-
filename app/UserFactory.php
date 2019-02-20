<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use app\HospitalAppointment;

class UserFactory extends Model
{
    /*
    * @param $type
    *  build a class based on the provided $type
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