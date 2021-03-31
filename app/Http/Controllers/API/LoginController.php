<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\ValidationException;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    //use AuthenticatesUsers;


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request){

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            //'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        //Para usar con devices
        //return $user->createToken($request->device_name)->plainTextToken;

        //Se borran los tokens usados en otros dispositivos
        $user->tokens()->delete();

        return response()->json([
            'user' => $user,
            'token' => $user->createToken("Browser")->plainTextToken,
        ]);
    }

    public function logout(Request $request){

        //Borra TODOS los tokens
        //$request->user()->tokens()->delete();

        //Borra SOLO el token actual
        $request->user()->currentAccessToken()->delete();

        //var_dump($request->user()->currentAccessToken()['id']);

        //Borra todos los tokens MENOS el actual
        //$request->user()->tokens()->where('id', '!=' ,$request->user()->currentAccessToken()['id'])->delete();

        return response()->json(['message' => 'Successfully logged out'],200);

    }

}
