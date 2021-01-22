<?php

namespace Modules\Auth\Http\Controllers\UserDashboard;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\EmailVerificationService;
use App\Services\AddressService;
use App\Providers\RouteServiceProvider;
use App\Notifications\User\EmailConfirmation;
use App\Models\User;
use App\Http\Controllers\Controller;

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

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    /**
     * @var AddressService
     */
    private $addressService;
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var EmailVerificationService
     */
    private $emailVerification;

    /**
     * Create a new controller instance.
     *
     * @param AddressService $addressService
     * @param UserService $userService
     * @param EmailVerificationService $emailVerification
     */
    public function __construct(
        AddressService $addressService,
        UserService $userService,
        EmailVerificationService $emailVerification
    ) {
        $this->middleware('guest');
        $this->addressService = $addressService;
        $this->userService = $userService;
        $this->emailVerification = $emailVerification;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(Request $request)
    {
        $attributes = $request->all();

        DB::transaction(function () use ($attributes) {

            if (isset($attributes['address'])) {
                $address = $this->addressService->store($attributes['address']);
            }

            $user = $this->userService->store([
                'first_name' => $attributes['first_name'],
                'last_name' => $attributes['last_name'],
                'username' => $attributes['username'],
                'date_of_birth' => $attributes['date_of_birth'],
                'address_id' => isset($address) ? $address->id : null,
                'password' => Hash::make($attributes['password']),
                'email' => $attributes['email'],
            ]);

            $this->emailVerification()->create([
                'verification_code' => Str::random(32),
            ]);

            $user->notify(new EmailConfirmation($user));

        }, 5);

        return response()->success('This action has been completed successfully');

    }
}
