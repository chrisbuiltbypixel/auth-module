@component('mail::message')
# Password Updated

Hi {{ $user->first_name }},

Your password has been reset successfully.

If you did not request your password to be changed please contact a member of our staff immediately.

@component('mail::button', ['url' => env('APP_URL') . '/auth/login'])
    Login To Dashboard
@endcomponent

Thanks,<br>
{{ env('APP_URL') }}
@endcomponent
