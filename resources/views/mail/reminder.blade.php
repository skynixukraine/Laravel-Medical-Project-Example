@component('mail::message')
# Hinweis zur verbleibenden Bearbeitungszeit

Ein Fall, zu dem Sie eine Rückfrage gestellt hatten,
muss in etwa {{ floor($submission->responsetime / 2) }} Stunden beantwortet sein.

Bitte denken Sie daran, dass Fälle, bei denen eine Rückfrage gestellt wurde, dauerhaft dem
Arzt zugewiesen werden, der die Frage gestellt hat.
Insofern kann auch kein anderer Dermatologe den Fall bearbeiten.

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
