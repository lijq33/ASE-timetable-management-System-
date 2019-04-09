<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $auth = '';
    protected $user = '';

    /**
     * Create a new AuthController instance.
     * The system will first check if the registration_type is not null and retrieve it
     * Depending on its registration type, the user will be given the appropriate access to their functions.
     *
     * @param \Illuminate\Http\Request $request - Contains the information of the user
     * @return void
     */
    public function __construct(Request $request)
    {
        // $this->middleware('auth:api', ['except' => ['login']]);

        $data = request()->all();

        if (isset($data['registration_type'])){
            $user_class = \App\UserFactory::build(ucfirst($data['registration_type']));

            if( is_a($user_class, \App\Individual::class)){
                $this->auth = auth('individual');
                $this->user = 'individual';
                $this->middleware('auth:individual', ['except' => ['login']]);

            }else{
                $this->auth = auth('company');
                $this->user = 'company';
                $this->middleware('auth:company', ['except' => ['login']]);
            }
        }
    }

    /**
     * Get a JWT via given credentials. 
     * If the token doesnt match up, the user will be denied access.
     *
     * @param \Illuminate\Http\Request $request - Contains the user login credentials
     * @return \Illuminate\Http\JsonResponse or a message
     */
    public function login(Request $request)
    {
        
        $credentials = request(['email', 'password']);

        if (! $token = $this->auth->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Authenticate if the current user is the correct user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json($this->auth->user());
    }

    /**
     * Log the current user out and invalidate the token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->auth->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->auth->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param $token - The current user's token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'user' => $this->guard()->user(),
            'token_type' => 'bearer',
            'expires_in' => $this->auth->factory()->getTTL() * 60
        ]);
    }

    /**
     * Used to authenticate and register user. 
     *
     * @return Auth::Guard.
     */
    public function guard() {
        return \Auth::Guard($this->user);
    }
}
