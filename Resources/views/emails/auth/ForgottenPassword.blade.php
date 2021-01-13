@component('mail::message')
# Reset Password Request

Hi,

You have requested for your password to be reset. Please click the link below to confirm your new password.

If you did not request for your password to be changed please contact a member of staff to discuss.

@component('mail::button', ['url' => env('APP_URL') . '/forgotten-password?token=' . $token->token])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
