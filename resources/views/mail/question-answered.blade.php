@component('mail::message')
# Ihre Rückfrage wurde soeben beantwortet,

Sie hatten eine Rückfrage zu einem eingereichten Fall gestellt.
Der Patient hat soeben darauf geantwortet.
Bitte denken Sie daran innerhalb der nächsten **{{ $submission->responsetime }} Stunden** den Befund zu schreiben.

@component('mail::button', ['url' => 'https://aerzte.online-hautarzt.net'])
Zum Ärzteportal
@endcomponent

{{--Bei Rückfragen steht ihnen unser Team zur Verfügung.--}}

Vielen Dank,<br>
Das Team von {{ config('app.name') }}

@component('mail::subcopy')
Falls Sie den Link "Zum Ärzteportal" nicht anklicken können, benutzen Sie bitte folgenden Link:
[https://aerzte.online-hautarzt.net](https://aerzte.online-hautarzt.net)
@endcomponent

@endcomponent
