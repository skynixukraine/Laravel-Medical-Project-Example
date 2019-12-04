@component('mail::message')
# Rückfrage zu Ihrem Fall,

Der bearbeitende Hautfacharzt hat eine Rückfrage zu Ihrem Fall.
Bitte öffnen Sie Ihren Fall und beantworten Sie die Frage, damit der Arzt eine abschließende Beurteilung abgeben kann.

@component('mail::button', ['url' => 'https://online-hautarzt.net/fall-ansehen/'])
Meinen Fall anzeigen
@endcomponent

Ihre Fallnummer ist: ** {{ $submission->submission_id }} **

{{--Bei Rückfragen steht ihnen unser Team zur Verfügung.--}}

Vielen Dank,<br>
Das Team von {{ config('app.name') }}

@component('mail::subcopy')
Falls Sie den Link "Meinen Fall anzeigen" nicht anklicken können, benutzen Sie bitte folgenden Link:
[https://online-hautarzt.net/fall-ansehen/](https://online-hautarzt.net/fall-ansehen/)
@endcomponent

@endcomponent
