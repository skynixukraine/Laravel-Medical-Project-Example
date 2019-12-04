@component('mail::message')
# Vielen Dank,

Ihr Fall liegt unseren Hautärzten vor und wird innerhalb der nächsten {{ $submission->responsetime }} Stunden bearbeitet.
@if ($submission->email && $submission->medium != "web" && $submission->device_id)
    Sobald ein Befund vorliegt werden Sie per Email informiert.<br>
    Zusätzlich senden wir Ihnen eine Nachricht auf ihr Smartphone.
@elseif ($submission->email)
    Sobald ein Befund vorliegt werden Sie per Email informiert.
@elseif ($submission->medium != "web" && $submission->device_id)
    Sobald ein Befund vorliegt schicken wir Ihnen eine Push Nachricht auf ihr Smartphone.
@endif

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
