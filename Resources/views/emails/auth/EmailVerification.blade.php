@component('mail::message')
# {{ env('APP_NAME') }} Email Verification

Hi {{ $user->first_name }},

Please click the link below to verify your email address with the {{ env('APP_NAME') }} system.

@component('mail::button', ['url' => env('APP_URL') . '/auth/email-verification?token=' .
$user->email_verification->verification_code])
Verify Email Address
@endcomponent

Thanks.
{{ env('APP_NAME') }}
@endcomponent