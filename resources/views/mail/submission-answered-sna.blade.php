@component('mail::message')
# Many thanks,

Your case has been answered by our experts.
The following link will take you to the findings.

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
