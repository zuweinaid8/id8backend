@component('mail::message')
# New Solution Created

A new solution has been created.

## Details

| | |
|:-------- |:------------------|
| <b>Title:</b> | {{$solution->title }} |
| <b>Description:</b>  | {{$solution->description }} |
| <b>Stage:</b>  | {{ $solution->stage->name }} |
| <b>Startup:</b>  | {{$solution->startup->name }} |


@component('mail::button', ['url' => 'https://admin.id8.space/#/solutions/' . $solution->id])
    View More Details
@endcomponent

Thanks,<br>
{{ config('app.name') }} team
@endcomponent

