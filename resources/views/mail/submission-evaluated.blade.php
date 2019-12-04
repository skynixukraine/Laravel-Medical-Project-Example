@component('mail::message')
# Bewertung abgeben,

Der Befund zum Fall {{ $submission->id }} wurde bewertet.

Bearbeitender Hautfacharzt: {{ $user->name() }}

**Sterne:** {{ $submission->stars }}

**Feedback:** {{ $submission->feedback }}

@endcomponent
