<!DOCTYPE html>{{ app()->setLocale('en') }}
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    {{--<link rel="icon" href="../../favicon.ico">--}}

    <title>Submit your case</title>

    <script src="{{ asset('js/jquery-3.4.1-min.js', substr(config('app.url'),0,5) == "https") }}"></script>
    <script src="{{ asset('js/bootstrap-3.3.7-min.js', substr(config('app.url'),0,5) == "https") }}"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
        const config_partner_id = "sna";
        const config_partner_language = "en";
    </script>
    <script src="js/case-submit-18558.js"></script>

    <script type="text/template" id="upload-template">
        <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Drop picture here">
            {{--<div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">--}}
                {{--<div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>--}}
            {{--</div>--}}
            <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                <span class="qq-upload-drop-area-text-selector"></span>
            </div>
            <div class="qq-upload-button-selector qq-upload-button">
                <div>Select photo</div>
            </div>
            <span class="qq-drop-processing-selector qq-drop-processing">
                <span>Processing dropped files...</span>
                <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
            </span>
            <ul class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
                <li>
                    <div class="qq-progress-bar-container-selector">
                        <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                    </div>
                    {{--<span class="qq-upload-spinner-selector qq-upload-spinner"></span>--}}
                    <img class="qq-thumbnail-selector" qq-max-size="130" qq-server-scale>
                    {{--<span class="qq-upload-file-selector qq-upload-file"></span>--}}
                    {{--<span class="qq-upload-size-selector qq-upload-size"></span>--}}
                    <button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Cancel</button>
                    <button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry">Try again</button>
                    <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">Delete</button>
                    <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                </li>
            </ul>

            <dialog class="qq-alert-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Close</button>
                </div>
            </dialog>

            <dialog class="qq-confirm-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">No</button>
                    <button type="button" class="qq-ok-button-selector">Yes</button>
                </div>
            </dialog>

            <dialog class="qq-prompt-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <input type="text">
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Cancel</button>
                    <button type="button" class="qq-ok-button-selector">Ok</button>
                </div>
            </dialog>
        </div>
    </script>
    <link href="{{ asset('css/web.css', substr(config('app.url'),0,5) == "https") }}" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    {{--<link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">--}}
</head>

<body id="case-submit" class="partner-sna">
<!-- --------------- -->
<!-- submission_form -->
<!-- --------------- -->
<div class="container" id="submission_form_container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form id="submission_form" class="form-horizontal">
                <input type="hidden" name="medium" id="submission_medium" value="web">
                <input type="hidden" name="partner_id" id="submission_partner_id" value="sna">
                <h2>1. Upload photos of the affected area</h2>
                <p class="col-sm-12" style="font-style: italic">By uploading an image, you agree to our <a href="{{ config('app.sna.MIX_WEB_URL') }}/privacy" target="_blank">privacy policy</a>.</p>
                <div class="form-group form-group-beschreibung">
                    <label class="col-sm-3 control-label">Overview image*</label>
                    <div class="col-sm-9">
                        <p id="submission_note_symptoms">Upload an overview image <b>(from a distance of 30 cm)</b> of the affected region.</p>
                        <div id="uploader-overview"></div>
                        <input type="hidden" name="overview_image_id" id="submission_overview_image_id" value="">
                        <p id="submission_overview_image_id_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group form-group-beschreibung">
                    <label class="col-sm-3 control-label">Close-up 1*</label>
                    <div class="col-sm-9">
                        <p id="submission_note_symptoms">Upload a close up image <b>(from a distance of 10 cm)</b> of the affected region.</p>
                        <div id="uploader-closeup"></div>
                        <input type="hidden" name="closeup_image_id" id="submission_closeup_image_id" value="">
                        <p id="submission_closeup_image_id_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group form-group-beschreibung">
                    <label class="col-sm-3 control-label">Close-up 2*</label>
                    <div class="col-sm-9">
                        <p id="submission_note_symptoms">Upload another close-up image <b>(from a distance of 10 cm)</b>.
                            This should be taken from a different angle than the first close-up.</p>
                        <div id="uploader-closeup2"></div>
                        <input type="hidden" name="closeup2_image_id" id="submission_closeup2_image_id" value="">
                        <p id="submission_closeup2_image_id_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <h2>2. Description</h2>
                <div class="form-group form-group-beschreibung">
                    <label class="col-sm-3 control-label" for="submission_email">Symptoms*</label>
                    <div class="col-sm-9">
                        <p id="submission_note_symptoms">Which smyptoms does your skin problem cause?<br>
                            Please select all that apply.
                        </p>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="1">Rash</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="2">Itching</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="3">Swelling</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="4">Redness</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="5">Pain</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="6">Flaking</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="7">Mole</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="8">Spots</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="9" id="symptom_sonstiges">Others</label></div>
                        <p id="submission_symptoms_error" class="submission_field_error" style="display: none"></p>
                        <input type="text" class="form-control" id="submission_other_symptoms" name="other_symptoms" placeholder="Symptoms" style="display: none">
                        <p id="submission_other_symptoms_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group form-group-beschreibung">
                    <label for="submission_side" class="col-sm-3 control-label">One side / both sides*</label>
                    <div class="col-sm-9">
                        <p>Does your problem occur on one side of your body or on both sides?<br>
                            (Only on right arm e.g. would mean one-sided, on both arms would mean both-sided.)
                        </p>
                        <label class="radio-inline">
                            <input type="radio" name="side" value="einseitig" class="submission_form_radio">one side
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="side" value="beidseitig" class="submission_form_radio">both sides
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="side" value="nicht sicher" class="submission_form_radio">not sure
                        </label>
                        <p id="submission_side_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group form-group-beschreibung">
                    <label class="col-sm-3 control-label" for="submission_affected_area">Affected region*</label>
                    <div class="col-sm-9">
                        <p>Where exactly is your skin problem located? At which part of your body does your skin problem show?</p>
                        <textarea class="form-control" id="submission_affected_area" name="affected_area" rows="2"></textarea>
                        <p id="submission_affected_area_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group form-group-beschreibung">
                    <label for="submission_since" class="col-sm-3 control-label">Since*</label>
                    <div class="col-sm-9">
                        <p>How long do have this problem?</p>
                        <select class="form-control" id="submission_since" name="since">
                            <option value="">- please select -</option>
                            <option value="weniger als zwei Tage">less than 2 days</option>
                            <option value="zwischen 2 bis 6 Tagen">between 2 and 6 days</option>
                            <option value="zwischen 1 bis 4 Wochen">between 1 and 4 weeks</option>
                            <option value="länger als 1 Monat">longer than 1 month</option>
                            <option value="chronisch/permanent">chronic/permanent</option>
                            <option value="andere Angabe">other</option>
                        </select>
                        <p id="submission_since_error" class="submission_field_error" style="display: none"></p>
                        <input type="text" class="form-control" id="submission_since_other" name="since_other" placeholder="Since when exactly?" style="display: none;">
                        <p id="submission_since_other_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group form-group-beschreibung">
                    <label for="submission_treated" class="col-sm-3 control-label">Treated so far*</label>
                    <div class="col-sm-9">
                        <p>Did you already use a medicine or cream/lotion to improve xour problem?</p>
                        <label class="radio-inline">
                            <input type="radio" name="treated" value="1" class="submission_form_radio">Yes
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="treated" value="0" class="submission_form_radio">No
                        </label>
                        <p id="submission_treated_error" class="submission_field_error" style="display: none"></p>
                        <textarea class="form-control" id="submission_treatment" name="treatment" rows="2" placeholder="Which drug or ointment did you use?" style="display: none"></textarea>
                        <p id="submission_treatment_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group form-group-beschreibung">
                    <label class="col-sm-3 control-label" for="submission_description">Additional information</label>
                    <div class="col-sm-9">
                        <p>Do you have additonal information relevant for your case?</p>
                        <textarea class="form-control" id="submission_description" name="description" rows="2"></textarea>
                        <p id="submission_description_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>

                <h2>3. More Details</h2>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <label class="radio-inline">
                            <input type="radio" name="gender" id="submission_gender_f" value="f" class="submission_form_radio"> Female
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gender" id="submission_gender_m" value="m" class="submission_form_radio"> Male
                        </label>
                        <p id="submission_gender_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="submission_age">Age</label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control" id="submission_age" name="age" min="0" max="100">
                        <p id="submission_age_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="submission_email">Email</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="submission_email" name="email">
                        <p id="submission_email_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="agb_accepted" id="submission_agb_accepted" value="true"> I accept the
                                <a href="{{ config('app.sna.MIX_WEB_URL') }}/terms" target="_blank">terms of service</a> and
                                <a href="{{ config('app.sna.MIX_WEB_URL') }}/privacy" target="_blank">privacy policy</a>.
                            </label>
                            <p id="submission_agb_accepted_error" class="submission_field_error" style="display: none"></p>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-primary btn-lg" id="submission_submit">Submit</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- ------------------ -->
<!-- show submission id -->
<!-- ------------------ -->
<div class="container" id="submission_created" style="display: none; margin-top: 30px">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="alert alert-info">
                <h2 style="margin-top: 10px">Your case has been created.</h2>
                <p>Please note down your case number now:</p>
                <p class="text-center" style="margin: 20px 0"><b class="lead" id="submission_created_submission_id" style="border: 1px solid #2ea3f2; padding: 5px"></b></p>
                <p>After the payment process your case number will not be shown again.</p>
                <p>If the payment process fails, you can restart it later by entering your case number here:
                    <a href="https://online-dermatologist.net/view-case/" target="_blank" style="font-weight: bold">My Case</a></p>
            </div>
        </div>
    </div>
</div>

<!-- ------------ -->
<!-- payment_form -->
<!-- ------------ -->
@component('components.payment')
@endcomponent

<p style="margin-top: 100px"><br></p>

</body>
</html>
