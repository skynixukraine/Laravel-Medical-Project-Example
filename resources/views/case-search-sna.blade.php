<!DOCTYPE html>{{ app()->setLocale('en') }}
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    {{--<link rel="icon" href="../../favicon.ico">--}}

    <title>Fallsuche</title>

    <script src="{{ asset('js/jquery-3.4.1-min.js', substr(config('app.url'),0,5) == "https") }}"></script>
    <script src="{{ asset('js/bootstrap-3.3.7-min.js', substr(config('app.url'),0,5) == "https") }}"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
        const config_partner_id = "sna";
        const config_partner_language = "en";
    </script>
    <script src="js/case-search-18558.js"></script>
    <link href="{{ asset('css/web.css', substr(config('app.url'),0,5) == "https") }}" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    {{--<link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">--}}
</head>

<body id="case-search" class="partner-sna">

<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <form class="case_search form-inline">
                <div class="form-group ">
                    <input type="text" name="case_search_submission_id" placeholder="Case number" class="case_search_submission_id form-control">
                </div>
                <input type="submit" class="btn btn-custom" value="Submit">
            </form>
        </div>
    </div>
</div>

<!-- ----------------------------- -->
<!-- case search error message box -->
<!-- ----------------------------- -->
<div id="case_search_error" style="display: none; max-width: 30%;" class="container"> <!-- position: fixed; top: 50px; right: 0; z-index: 10 -->
    <div style="padding: 5px;">
        <div id="case_search_error_inner" class="alert alert-danger">
            {{--<button type="button" class="close" data-dismiss="alert">&times;</button>--}}
            <p id="case_search_error_message"></p>
        </div>
    </div>
</div>

<!-- ---------------- -->
<!-- case search data -->
<!-- ---------------- -->
<div id="case_search_data" class="container" style="display: none;">
    <div class="row">
        <div class="col-md-12">
            <ul id="tabs-setup" class="nav nav-tabs" style="display: none;">
                <li><a data-toggle="tab" href="#case_search_payment">Payment</a></li>
                <li><a data-toggle="tab" href="#case_search_my_case">My Case</a></li>
            </ul>
            <ul id="tabs-default" class="nav nav-tabs" style="display: none;">
                <li><a data-toggle="tab" href="#case_search_result">Medical Results</a></li>
                <li><a data-toggle="tab" href="#case_search_my_case">My Case</a></li>
                <li><a data-toggle="tab" href="#case_search_questions">Questions <span class="badge" id="questions_badge" style="display:none">!</span></a></li>
                <li><a data-toggle="tab" href="#case_search_evaluation">Rating</a></li>
            </ul>
            <div class="tab-content">
                <div id="case_search_payment" class="tab-pane fade">
                    <div class="row">
                        <div class="col-xs-9 col-sm-12">
                            @component('components.payment')
                            @endcomponent
                        </div>
                    </div>
                </div>
                <div id="case_search_result" class="tab-pane fade">
                    <div class="row">
                        <div id="case_search_status_open" class="col-md-12" style="display: none;">
                            <div class="alert alert-warning" role="alert">
                                <strong>Your case has not been processed yet.</strong><br>
                                <span id="case_search_result_open" style="display: none">
                                    <span id="case_search_time_left"></span>
                                </span>
                            </div>
                        </div>
                        <div id="case_search_status_answered" class="col-md-12" style="display: none;">
                            <div class="alert alert-success" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>The results of your case are available.</strong>
                            </div>
                        </div>
                        <div class="col-sm-9 col-md-10">
                            <div id="case_search_result_answered" style="display: none">
                                <blockquote>
                                    <p id="case_search_answer" class="case_search_info"></p>
                                    <footer>
                                        <span class="case_search_answered_by_name case_search_info"></span>, <span id="case_search_answered_at" class="case_search_info"></span>
                                    </footer>
                                </blockquote>
                            </div>
                            <p>Case number: <span id="case_search_submission_id" class="case_search_info"></span></p>
                        </div>
                        <div class="col-sm-3 col-md-2">
                            <div id="case_search_answered_by">
                                <figure>
                                    <img src="" id="case_search_answered_by_photo" class="img-responsive">
                                </figure>
                                <p class="case_search_answered_by_name case_search_info"></p>
                                <p>
                                    <span id="case_search_answered_by_city" class="case_search_info"></span>,
                                    <span id="case_search_answered_by_country" class="case_search_info"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="case_search_my_case" class="tab-pane fade">
                    <div class="row">
                        <div class="col-sm-8">
                            {{--<p>Einseitig/Beidseitig: beidseitig</p>--}}
                            <table class="properties">
                                <tr><td class="property">Symptoms</td><td class="value"><span id="case_search_symptoms" class="case_search_info"></span></td></tr>
                                <tr><td class="property">One-sided / both-sided</td><td class="value"><span id="case_search_side" class="case_search_info"></span></td></tr>
                                <tr><td class="property">Location</td><td class="value"><span id="case_search_affected_area" class="case_search_info"></span></td></tr>
                                <tr><td class="property">Since</td><td class="value"><span id="case_search_since" class="case_search_info"></span></td></tr>
                                <tr><td class="property">Used medicine</td><td class="value"><span id="case_search_treated" class="case_search_info"></span></td></tr>
                                <tr><td class="property">Additional Information</td><td class="value"><span id="case_search_description" class="case_search_info"></span></td></tr>

                                <tr><td class="property">Sex</td><td class="value"><span id="case_search_gender" class="case_search_info"></span></td></tr>
                                <tr><td class="property">Age</td><td class="value"><span id="case_search_age" class="case_search_info"></span></td></tr>
                            </table>
                        </div>
                        <div class="col-sm-4">
                            <div class="row" style="margin-top: 20px">
                                <div id="case_search_photo_overview_wrapper">
                                    <figure>
                                        <img src="" id="case_search_photo_overview" class="img-responsive">
                                        <figcaption id="case_search_photo_overview_caption">Overview image</figcaption>
                                    </figure>
                                </div>
                            </div>
                            <div class="row">
                                    <div id="case_search_photo_closeup_wrapper">
                                        <figure>
                                            <img src="" id="case_search_photo_closeup" class="img-responsive">
                                            <figcaption id="case_search_photo_closeup_caption">Close-up 1</figcaption>
                                        </figure>
                                    </div>
                            </div>
                            <div class="row">
                                    <div id="case_search_photo_closeup_wrapper">
                                        <figure>
                                            <img src="" id="case_search_photo_closeup2" class="img-responsive">
                                            <figcaption id="case_search_photo_closeup2_caption">Close-up 2</figcaption>
                                        </figure>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="case_search_questions" class="tab-pane fade">
                    <div class="row">
                        <div class="col-xs-9 col-sm-12">
                            <div id="case_search_questions_none" style="display: none">
                                <div class="alert alert-success" role="alert">
                                    <p><strong>Currently, there are no questions.</strong><br>
                                        If the dermatologist has a question regarding your case,
                                        you can read and answer the question here.
                                        You will be notified by email.</p>
                                </div>
                            </div>
                            <div id="case_search_questions_list" style="display: none">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="case_search_evaluation" class="tab-pane fade">
                    <div class="row">
                        <div class="col-xs-9 col-sm-12">
                            <form id="case_search_evaluation_form" class="form-horizontal" style="display: none">
                                <p>We would be delighted if you could provide feedback about how you liked our service.<p>
                                    <div class="form-group form-group-stars">
                                        <label for="submission_feedback" class="col-sm-2 control-label">Stars</label>
                                        <div class="col-sm-7">
                                            <div id="stars" class="starrr"></div>
                                            <input type="hidden" id="submission_stars" name="stars">
                                <p id="evaluation_error_stars" style="display: none;" class="text-danger"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="submission_feedback" class="col-sm-2 control-label">Feedback</label>
                        <div class="col-sm-7">
                            <input type="text" name="feedback" class="form-control" id="submission_feedback">
                            <p id="evaluation_error_feedback" style="display: none;" class="text-danger"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-7">
                            <button type="submit" class="btn btn-custom">Submit</button>
                        </div>
                    </div>
                    </form>
                    <div id="case_search_evaluation_answered" style="display: none">
                        <div class="form-horizontal">
                            <h4>Thank you for rating our service.</h4>
                            <p>Your rating:</p>
                            <div class="form-group form-group-stars">
                                <label for="case_search_evaluation_answered_stars" class="col-sm-2 control-label">Stars</label>
                                <div class="col-sm-7">
                                    <p id="case_search_evaluation_answered_stars" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="case_search_evaluation_answered_feedback" class="col-sm-2 control-label">Feedback</label>
                                <div class="col-sm-7">
                                    <p class="form-control-static" id="case_search_evaluation_answered_feedback"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="case_search_evaluation_open" style="display: none">
                        <p>We would be pleased if you evaluate our service as soon as this is present.<br>
                           Your feedback will not be shown to the Dermatologist or any other user.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

</body>
</html>