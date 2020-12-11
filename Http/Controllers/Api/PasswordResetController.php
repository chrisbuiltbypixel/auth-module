<?php

namespace Modules\Auth\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\User\ForgottenPassword as Mailable;
use App\Http\Controllers\Controller;

class PasswordResetController extends Controller
{
    public function sendPasswordResetToken(Request $request)
    {
        $attributes = $request->all();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->error($validator->errors());
        }

        $user = User::where('email', $attributes['email'])->first();

        if (!$user) {
            return response()->error('No user exists with that email');
        }

        //create a new token to be sent to the user.
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Str::random(60),
            'created_at' => \Carbon\Carbon::now(),
        ]);

        $token = DB::table('password_resets')
            ->where('email', $request->email)->first();

        $email = $attributes['email']; // or $email = $tokenData->email;

        Mail::to($user)->send(new Mailable($user, $token));

        return response()->success('A password request email has been sent');
    }

    public function resetPassword(Request $request)
    {
        $attributes = $request->all();

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'confirmed',
        ]);

        if ($validator->fails()) {
            return response()->error($validator->errors());
        }

        $password = $attributes['password'];
        $tokenData = DB::table('password_resets')->where('token', $attributes['token'])->first();

        if (!$tokenData) {
            return response()->error('Could not find token');
        }

        $user = User::where('email', $tokenData->email)->first();

        if (!$user) {
            return response()->error('Could not find a user for this token');
        }

        $user->password = Hash::make($password);
        $user->save();

        // If the user shouldn't reuse the token later, delete the token
        DB::table('password_resets')->where('email', $user->email)->delete();

        return response()->success('You have successfully changed your password');
    }
}
