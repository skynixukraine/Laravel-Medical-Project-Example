@component('mail::message')
# Question about your case,

The Dermatologist who is working on your case asked a question about it.
Please open your case and answer the question so that the doctor can make a final assessment.

@component('mail::button', ['url' => 'https://online-dermatologist.net/view-case/'])
My Case
@endcomponent

Your case number is: ** {{ $submission->submission_id }} **

{{--Bei Rückfragen steht ihnen unser Team zur Verfügung.--}}

Many thanks,<br>
The team of {{ config('app.name') }}

@component('mail::subcopy')
If you can not click on the link "My Case", please use the following link:
[https://online-dermatologist.net/view-case/](https://online-dermatologist.net/view-case/)
@endcomponent

@endcomponent
