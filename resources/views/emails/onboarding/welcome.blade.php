@component('mail::message')
# # We're glad you're here!

We would like to welcome you to the I

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
