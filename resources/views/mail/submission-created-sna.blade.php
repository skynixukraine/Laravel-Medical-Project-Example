@component('mail::message')
# Many thanks,

Your case is available to our specialists and will be processed within the next {{ $submission->responsetime }} hours.
@if ($submission->email && $submission->medium != "web" && $submission->device_id)
    As soon as a report is available you will be informed by email.<br>
    In addition, we will send you a push message on your smartphone.
@elseif ($submission->email)
    As soon as a report is available you will be informed by email.
@elseif ($submission->medium != "web" && $submission->device_id)
    As soon as we have a result, we will send you a push message on your smartphone.
@endif

The following link will take you to the medical result.

@component('mail::button', ['url' => 'https://online-dermatologist.net/view-case/'])
Show result
@endcomponent

Your case number is: ** {{ $submission->submission_id }} **

{{--Bei Rückfragen steht ihnen unser Team zur Verfügung.--}}

Many thanks,<br>
The team of {{ config('app.name') }}

@component('mail::subcopy')
If you can not click on the link "Show result", please use the following link:
[https://online-dermatologist.net/view-case/](https://online-dermatologist.net/view-case/)
@endcomponent

@endcomponent
