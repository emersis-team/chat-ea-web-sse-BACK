<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        //Chequea info enviada
        $campos = request()->validate([
            'email' => ['required','email'],
            'password' => 'required',
        ]);

        $credentials = request(['email', 'password']);

        //if (! $token = auth()->attempt($credentials)) {
        if (! $token = Auth::attempt($credentials)) {

            return response()->json(['error' => 'Unauthorized'], 401);
        }

        //var_dump("Token: " . $token);
        //var_dump("Attemp: ". Auth::attempt($credentials) );

        $user = User::where('email',$campos['email'])
                            -> first();

        return response()->json([
            'user' => $user,
            'token' => $token,
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = Auth::user();

        return response()->json([
            'user' => $user,
            'token' => $token,
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }
}
