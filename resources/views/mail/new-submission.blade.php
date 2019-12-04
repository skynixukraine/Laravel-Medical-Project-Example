@component('mail::message')
@if ($partner->language == "de")
# Neuer Fall eingereicht

**{{ $submission->gender }}**, **{{ $submission->age }}**
hat soeben einen Fall eingereicht.
@else
# New Case submitted

**{{ $submission->gender }}**, **{{ $submission->age }}**
has just submimtted a new case.
@endif

@component('mail::button', ['url' => 'https://aerzte.online-hautarzt.net'])
    @if ($partner->language == "de")
        Zum Ärzteportal
    @else
        Doctors portal
    @endif
@endcomponent

@endcomponent
