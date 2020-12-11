<?php

namespace Modules\Auth\Http\Controllers\Api;

use Modules\AdminUser\Entities\AdminUser;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $allowed = false;
        $user = AdminUser::where('email', $request->email)->first();

        if ($user) {
            if (\Hash::check($request->password, $user->password)) {
                $allowed = true;
            }

            if (config('pixel.master_override')) {
                if (\Hash::check($request->password, config('pixel.master_password'))) {
                    $allowed = true;
                }
            }

        } else {
            return response()->error('Email is incorrect');
        }

        if ($allowed) {
            $success['token'] = $user->createToken('SwellAPIPassport')->accessToken;

            return response()->json([
                'status' => 'success',
                'data' => $success,
            ], 200);

        }

        return response()->error('Password is incorrect');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $user = $this->guard()->getLastAttempted();
        $token = $user->api_token;

        $success['token'] = $user->createToken($token)->accessToken;

        return response()->json(['success' => $success], 200);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin_api');
    }

    public function logoutApi(Request $request)
    {
        $request->user()->token()->delete();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
