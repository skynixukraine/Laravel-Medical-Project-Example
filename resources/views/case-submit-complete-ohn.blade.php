<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    {{--<link rel="icon" href="../../favicon.ico">--}}

    <script src="{{ asset('js/jquery-3.4.1-min.js', substr(config('app.url'),0,5) == "https") }}"></script>
    <script src="{{ asset('js/bootstrap-3.3.7-min.js', substr(config('app.url'),0,5) == "https") }}"></script>
    <script type="text/javascript">
        const config_partner_id = "ohn";
    </script>
    <script src="{{ asset('js/case-submit-18558.js', substr(config('app.url'),0,5) == "https") }}"></script>

    <link href="{{ asset('css/web.css', substr(config('app.url'),0,5) == "https") }}" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    {{--<link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">--}}
</head>

<body id="case-submit">

<div class="container" id="submission_complete_container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @if (isset($paymentError))
                <div class="alert alert-warning">
                    <h2>Bezahlvorgang abgebrochen.</h2>
                    <p>
                        Leider wurde der Bezahlvorgang abgebrochen.<br>
                        Dadurch wurde Ihr Fall nicht an unsere Fachärzte abgeschickt.<br><br>
                        Falls Sie gern jetzt Ihren Fall abschließen möchten, können Sie dies hier tun:<br><br>
                        <a href="https://online-hautarzt.net/fall-ansehen/" target="_top">
                            <button type="button" class="btn btn-primary">Fall abschliessen</button>
                        </a>
                    </p>
                </div>
            @else
                <div class="alert alert-info">
                    <h2>Ihr Fall wurde übermittelt.</h2>
                    <p>Ihr Fall wird innerhalb von {{ $submission->responsetime }} Stunden bearbeitet.<br>
                        @if ($submission->email && $submission->medium != "web" && $submission->device_id)
                            Sobald ein Befund vorliegt werden Sie per Email informiert.<br>
                            Zusätzlich senden wir Ihnen eine Nachricht auf Ihr Smartphone.
                        @elseif ($submission->email)
                            Sobald ein Befund vorliegt werden Sie per Email informiert.
                        @elseif ($submission->medium != "web" && $submission->device_id)
                            Sobald ein Befund vorliegt schicken wir Ihnen eine Nachricht auf ihr Smartphone.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>


</body>
</html>
