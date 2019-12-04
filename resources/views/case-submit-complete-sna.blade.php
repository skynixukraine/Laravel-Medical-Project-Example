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
        const config_partner_id = "sna";
    </script>
    <script src="{{ asset('js/case-submit-18558.js', substr(config('app.url'),0,5) == "https") }}"></script>

    <link href="{{ asset('css/web.css', substr(config('app.url'),0,5) == "https") }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    {{--<link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">--}}
</head>

<body id="case-submit" class="partner-sna">

<div class="container" id="submission_complete_container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @if (isset($paymentError))
                <div class="alert alert-warning">
                    <h2>Payment process cancelled.</h2>
                    <p>
                        Unfortunately, the payment process has been cancelled.<br>
                        Thus your case was not sent to our doctors.<br><br>
                        If you would like to submit your case now, you can do so here:<br><br>
                        <a href="https://online-dermatologist.net/view-case/" target="_top">
                            <button type="button" class="btn btn-primary">My case</button>
                        </a>
                    </p>
                </div>
            @else
                <div class="alert alert-info">
                    <h2>Your case has been submitted.</h2>
                    <p>Your case will be processed within {{ $submission->responsetime }} hours.<br>
                       @if ($submission->email && $submission->medium != "web" && $submission->device_id)
                          As soon as a report is available, you will be informed by email.<br>
                          In addition, we will send you a message on your smartphone.
                       @elseif ($submission->email)
                          As soon as a report is available, you will be informed by email.
                       @elseif ($submission->medium != "web" && $submission->device_id)
                          As soon as we have a result, we will send you a message on your smartphone.
                       @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>


</body>
</html>
