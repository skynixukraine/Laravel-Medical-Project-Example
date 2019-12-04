<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    {{--<link rel="icon" href="../../favicon.ico">--}}
    <script src="{{ asset('js/jquery-3.4.1-min.js', substr(config('app.url'),0,5) == "https") }}"></script>
    @if ($submission->medium == "ios")
        @if ($submission->partner_id == 1)
            @if ($transaction_status == "successful")
                <meta http-equiv="refresh" content="0; URL=com.brinker.whatsthat.payments://creditcard?mode=success">
            @else
                <meta http-equiv="refresh" content="0; URL=com.brinker.whatsthat.payments://creditcard?mode=failure">
            @endif
        @elseif ($submission->partner_id == 2)
            @if ($transaction_status == "successful")
                <meta http-equiv="refresh" content="0; URL=com.brinker.intimarzt.payments://creditcard?mode=success">
            @else
                <meta http-equiv="refresh" content="0; URL=com.brinker.intimarzt.payments://creditcard?mode=failure">
            @endif
        @else
            @if ($transaction_status == "successful")
                <meta http-equiv="refresh" content="0; URL=com.brinker.snapdoc.payments://creditcard?mode=success">
            @else
                <meta http-equiv="refresh" content="0; URL=com.brinker.snapdoc.payments://creditcard?mode=failure">
            @endif
        @endif
    @elseif ($submission->medium == "android")
        @if ($transaction_status == "successful")
            <meta http-equiv="refresh" content="0; URL=appdoc://creditcardpayment/?mode=success">
        @else
            <meta http-equiv="refresh" content="0; URL=appdoc://creditcardpayment/?mode=failure">
        @endif
    @endif
</head>

<body>
    <p>Vielen Dank. Sie werden jetzt zurück zur App geleitet.</p>
</body>
</html>