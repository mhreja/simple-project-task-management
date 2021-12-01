@component('mail::message')
# Your Account Has Been Created

<p>Hello {{$first_name}}, <br> 
    Greetings from {{config('app.name')}}. Please login to your account and reset your password.</p>

<br><br>
<p><b>Login Credentials-</b></p>
<p><i>Email: {{$email}} <br>
Password: {{$password}}</i></p>

@component('mail::button', ['url' => config('app.url') ])
Login Now
@endcomponent

Thanks & Regards,<br>
{{ config('app.name') }} Team 
@endcomponent
