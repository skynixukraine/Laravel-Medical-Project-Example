<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    {{--<link rel="icon" href="../../favicon.ico">--}}
    <title>ohn Demopage</title>
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
            <a class="navbar-brand" href="#">OHN</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <div class="navbar-form navbar-right">
                <a href="http://aerzte.fachportal.localhost">Login Ärzte</a>
            </div>
        </div><!--/.navbar-collapse -->
    </div>
</nav>

<!-- case search -->
<div class="jumbotron" style="background-color: white">
    <div class="container">
        <h2>Online Hautarzt.net - AppDoc</h2>
        <p>Geben Sie Ihre Fallnummer hier ein:</p>
        <iframe id="iframeCaseSearch" src="{{ config('app.MIX_API_URL') }}/case-search-ohn" style="width: 100%; border: none; margin: 0;"></iframe>
    </div>
</div>

<!-- case submit -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Fall anlegen</h2>
            <div id="iframe-case-submit-container"></div>
            <iframe id="iframeCaseSubmit" src="{{ config('app.MIX_API_URL') }}/case-submit-ohn" style="width: 100%; border: none; margin: 0;"></iframe>
        </div>
    </div>
</div>

<div class="container">
    <!-- Example row of columns -->
    <div class="row">
        <div class="col-md-4">
            <h2>Sie sorgen sich wegen eines Hautproblems?</h2>
            <p>Bei AppDoc erhalten Sie schnell, ortsunabhängig und kostengünstig (35.- Euro) eine fachärztliche Einschätzung Ihres Hautproblems. Dank der Handlungsempfehlung wissen Sie zudem, was Sie dagegen tun können.</p>
            <p>Wenden Sie sich jetzt an einen unserer deutschen Fachärzte für Hauterkrankungen.</p>
        </div>
        <div class="col-md-4">
            <h2>Heading</h2>
            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
            <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
        <div class="col-md-4">
            <h2>Heading</h2>
            <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
            <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
    </div>
    <hr>
    <footer>
        <p>&copy; 2018 online hautarzt.net</p>
    </footer>
</div> <!-- /container -->
<script type="text/javascript">
    // ATTENTION: In production case-search and case-submit are 2 different pages
    //            Each page should only listen to its own events !!
    window.addEventListener("message", function(evt) {
        var targetData = evt.data;
        // var targetSource = evt.source;
        // var targetOrigin = evt.origin;
        if (typeof targetData === 'object') {
            if (targetData.hasOwnProperty('caseSearch') ) {
                if (targetData.caseSearch.iframeHeight) {
                    // console.log("...caseSearch height ..." + targetData.caseSearch.iframeHeight);
                    jQuery('#iframeCaseSearch').height(targetData.caseSearch.iframeHeight);
                }
            }
            if (targetData.hasOwnProperty('caseSubmit') ) {
                if (targetData.caseSubmit.iframeHeight) {
                    // console.log("...caseSubmit height ..." + targetData.caseSubmit.iframeHeight);
                    jQuery('#iframeCaseSubmit').height(targetData.caseSubmit.iframeHeight);
                }
            }
        }
    });
</script>
</body>
</html>
