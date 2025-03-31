@component('mail::message')
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ config('app.url') }}/images/logo.svg" alt="{{ config('app.name') }}" style="width: 100px; height: auto;">
</div>

# Welcome to E-Nirikshan!

Hello {{ $user->name }},

Your account has been successfully created on the E-Nirikshan Inspection Portal. You can login using the following credentials:

**Username:** {{ $user->pf_no }} <br>
**Initial Password:** Your date of birth in DDMMYYYY format (e.g., for 19/11/1997, use 19111997)

For security reasons, please change your password after your first login.

## Getting Started

After logging in, you can:
* Change your password
* Diarise Inspections
* Check pending deficiencies

@component('mail::button', ['url' => url('/login')])
Login Now
@endcomponent

> If you have any issues logging in, please contact our support team . <br>
Email: rjtwritcell@gmail.com

Thanks,<br>
{{ config('app.name') }}

@endcomponent