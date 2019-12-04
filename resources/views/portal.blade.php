<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    {{--<link rel="icon" href="../../favicon.ico">--}}
    <title>Ärzteportal@Online-Hautarzt.net</title>
    <link href="css/app.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    {{--<link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">--}}
</head>

<body>
<div id="app">
    <navigation v-bind:logged-in="loggedIn" @logout="logout"></navigation>
    <statusbar v-bind:status="status"></statusbar>
    <div class="container">
        <router-view></router-view>
    <hr>
        <footer>
            <p>&copy; 2018 online-hautarzt.net - AppDoc</p>
        </footer>
    </div> <!-- /container -->
</div>

<script src="js/app.js"></script>
</body>
</html>