@component('mail::message')
# New User Registered

A new user has been registered.

## Details

| | |
|:--------| :------------------|
| <b>Email:</b> | {{$user->email }} |
| <b>Name:</b>  | {{$user->name }} |

<br/>


Thanks,<br>
{{ config('app.name') }} team
@endcomponent
