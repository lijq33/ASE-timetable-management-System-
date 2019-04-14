<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * The system will redirect the user to our homepage once their registration is successful.
     *
     * @var string
     */
    protected $redirectTo = '/home';


    /**
     * The new user information will be stored in our database.
     * The system will check for the registration type so that we can store the information in the correct table.
     * After which, the system will use a set of rules defined by us to check if all the information provided is in the correct format.
     * Appropriate error message will be displayed to let the user know which information is in the wrong format.
     * The user data will then be stored into our database.
     * A successful message will be displayed after the storing has been completed.
     *
     * @param  \Illuminate\Http\Request $request - Contains all the information regarding the new user.
     * @return \Illuminate\Http\JsonResponse - Display of successful message.
     */
    protected function store(Request $request)
    {
        $data = request()->all();

        $user_class = \App\UserFactory::build(ucfirst($data['registration_type']));

        $validator = $user_class->createAccount($data);
        
        if (isset($validator)) {
            if ($validator->fails()){
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }
        }

        return response()->json([
            'message' => 'Account is created successfully'
        ], 200);
    }
}
