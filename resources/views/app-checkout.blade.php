<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    {{--<link rel="icon" href="../../favicon.ico">--}}
    <title>Creditcard</title>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ asset('js/jquery-3.4.1-min.js', substr(config('app.url'),0,5) == "https") }}"></script>
    <script src="{{ asset('js/app-checkout-18558.js', substr(config('app.url'),0,5) == "https") }}"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    {{--<link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">--}}
</head>
<body>
<p>Sie werden jetzt zur Kreditkartenzahlung weitergeleitet...</p>
<p id="card-errors" style="color: red"></p>
<div id="stripekey" data-stripekey="{{ config('services.stripe.key') }}" style="display:none"></div>
</body>
</html>