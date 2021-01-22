<?php
namespace Modules\Auth\Http\Controllers\UserDashboard;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Controllers\Controller;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//    protected $redirectTo = '/';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('guest')->except('logout');
    }
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
        return response()->error('Invalid email or password', 401);
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

        if ($user->email_verified) {
            $token = $user->api_token;
            $success['token'] = $user->createToken($token)->accessToken;
            return response()->json(['success' => $success], 200);
        } else {
            return response()->json(['error' => 'Your email has not been verified yet'], 401);
        }
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
        return Auth::guard('user');
    }
    public function logoutApi(Request $request)
    {
        if (is_null($request->user())) {
            abort(401, 'You are not logged in');
        }
        $request->user()->token()->delete();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
