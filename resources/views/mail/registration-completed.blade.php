@component('mail::message')
# Registrierung als Dermatologe eingegangen.

Sie haben sich als Dermatologe auf online-hautarzt.net registriert.
Wir werden ihre Bewerbung prüfen und uns innerhalb von 24 Stunden bei Ihnen melden.

Sobald ihr Account freigeschaltet ist, können sie sich mit ihrer Email und Passwort
hier einloggen:

@component('mail::button', ['url' => config("app.MIX_PORTAL_URL")])
Login Ärzteportal
@endcomponent

Vielen Dank,<br>
Das Team von {{ config('app.name') }}

@component('mail::subcopy')
Falls Sie den Link "Login Ärtzeportal" nicht anklicken können, benutzen Sie bitte folgenden Link:
[{{ config('app.MIX_PORTAL_URL') }}]({{ config('app.MIX_PORTAL_URL') }})
@endcomponent

@endcomponent
