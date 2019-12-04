@component('mail::message')
# Vielen Dank,

Ihr Fall ist von unseren Experten beantwortet worden.
Über folgenden Link gelangen Sie zum Befund.

@component('mail::button', ['url' => 'https://online-hautarzt.net/fall-ansehen/'])
Befund anzeigen
@endcomponent

Ihre Fallnummer ist: ** {{ $submission->submission_id }} **

{{--Bei Rückfragen steht ihnen unser Team zur Verfügung.--}}

Vielen Dank,<br>
Das Team von {{ config('app.name') }}

@component('mail::subcopy')
Falls Sie den Link "Befund anzeigen" nicht anklicken können, benutzen Sie bitte folgenden Link:
[https://online-hautarzt.net/fall-ansehen/](https://online-hautarzt.net/fall-ansehen/)
@endcomponent

@endcomponent
