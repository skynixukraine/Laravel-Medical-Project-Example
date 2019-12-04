@component('mail::message')
# Lieber Nutzer/liebe Nutzerin von Intimarzt.de,

es schaut danach aus, dass Sie gerade eben Ihren Fall vollständig eingegeben haben,
aber ihn letztendlich nicht an unsere Fachärzte abgeschickt haben.

Falls ein Problem bei der Bezahlung aufgetreten ist, antworten Sie uns gern auf diese E-Mail.
Wir werden dann schnellstmöglich versuchen, den Fehler zu beheben.

Die Bearbeitungszeit beträgt derzeit durchschnittlich weniger als zwei Stunden,
egal welchen maximalen Bearbeitungszeitraum Sie wählen.
Entsprechend erhalten Sie eine professionelle Einschätzung Ihres Problems in kürzester Zeit.
Nur Hautfachärzte mit mindestens 10 Jahren klinischer Erfahrung aus Heidelberg dürfen bei
unserem Intimarzt-Dienst mitarbeiten.
In etwa 70% der Fälle können wir den Patienten so weiter helfen, dass sie nicht mehr in die Praxis müssen.
Unser Service ist der einzige von einer Landesärztekammer genehmigte Online-Intimarzt-Dienst in Deutschland
und zu 100% ein deutsches Produkt.

@component('mail::button', ['url' => 'https://intimarzt.de/fall-ansehen/'])
    Meinen Fall anzeigen
@endcomponent

{{--Bei Rückfragen steht ihnen unser Team zur Verfügung.--}}

Mit freundlichen Grüßen,<br>
Ihr Intimarzt-Team

@component('mail::subcopy')
Falls Sie den Link "Meinen Fall anzeigen" nicht anklicken können, benutzen Sie bitte folgenden Link:
[https://intimarzt.de/fall-ansehen/](https://intimarzt.de/fall-ansehen/)
@endcomponent

@endcomponent
