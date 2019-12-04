<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    {{--<link rel="icon" href="../../favicon.ico">--}}
    <title>Authorize Giropayment</title>
    <style>
        body {
            padding-top: 50px;
            padding-bottom: 20px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    {{--<link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">--}}
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">OHN / ITA</a>
        </div>
    </div>
</nav>

<div class="container" id="submission_form_container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <p>This blade view for /giropay (resources/views/giropay.blade.php) is only used for development and staging.<br>
                In production /giropay is a page, which is setup in wordpress.</p>
        </div>
    </div>
</div>

<div class="jumbotron" style="background-color: white">
    <div class="container">
        <div id="authorizeGiropay"></div>
    </div>
</div>

<script type="text/javascript">
    // document.location.search also includes partner_id now!
    $('<iframe>', {
        src: '{{ config('app.MIX_API_URL') }}/authorizegiropay' + document.location.search,
        id:  'iframeGiropay'
    }).css({
      width: '100%',
      height: '40px',
      border: 'none',
      margin: 0,
      background: 'url("https://api.online-hautarzt.net/images/spinner_blau.svg") no-repeat center'
    }).appendTo('#authorizeGiropay');

    // The iframe /authorizegiropay is handled by StripeController which returns the view case-submit-complete-[partner].blade.php
    // This view loads case-submit-18558.js, which every 1000ms sends an post message {"caseSubmit" : {"iframeHeight": x }}
    window.addEventListener("message", function(evt) {
        var targetData = evt.data;
        if (typeof targetData === 'object') {
            if (targetData.hasOwnProperty('caseSubmit') ) {
                if (targetData.caseSubmit.iframeHeight) {
                    // console.log("...giropay height ..." + targetData.caseSubmit.iframeHeight);
                    jQuery('#iframeGiropay').height(targetData.caseSubmit.iframeHeight);
                }
            }
        }
    });
</script>
</body>
</html>