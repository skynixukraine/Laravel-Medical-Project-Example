<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    {{--<link rel="icon" href="../../favicon.ico">--}}

    <title>Fall einreichen</title>

    <script src="{{ asset('js/jquery-3.4.1-min.js', substr(config('app.url'),0,5) == "https") }}"></script>
    <script src="{{ asset('js/bootstrap-3.3.7-min.js', substr(config('app.url'),0,5) == "https") }}"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
        const config_partner_id = "ohn";
        const config_partner_language = "de";
    </script>
    <script src="js/case-submit-18558.js"></script>

    <script type="text/template" id="upload-template">
        <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Foto hier ablegen">
            {{--<div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">--}}
                {{--<div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>--}}
            {{--</div>--}}
            <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                <span class="qq-upload-drop-area-text-selector"></span>
            </div>
            <div class="qq-upload-button-selector qq-upload-button">
                <div>Foto auswählen</div>
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
                    <button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Abbrechen</button>
                    <button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry">Nochmal versuchen</button>
                    <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">Löschen</button>
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

<body id="case-submit" class="partner-ohn">
<!-- --------------- -->
<!-- submission_form -->
<!-- --------------- -->
<div class="container" id="submission_form_container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form id="submission_form" class="form-horizontal">
                <input type="hidden" name="medium" id="submission_medium" value="web">
                <input type="hidden" name="partner_id" id="submission_partner_id" value="ohn">
                <h2>1. Laden Sie Fotos der betroffenen Region hoch</h2>
                <p class="col-sm-12" style="font-style: italic">Durch das Hochladen eines Bildes erklären Sie sich mit unserer <a href="{{ config('app.MIX_WEB_URL') }}/privacy" target="_blank">Datenschutzerklärung</a> einverstanden.</p>
                <div class="form-group form-group-beschreibung">
                    <label class="col-sm-3 control-label">Übersichtsaufnahme*</label>
                    <div class="col-sm-9">
                        <p id="submission_note_symptoms">Laden Sie hier eine Übersichtsaufnahme <b>(aus 30 cm Entfernung)</b> der betroffenen Region hoch.</p>
                        <div id="uploader-overview"></div>
                        <input type="hidden" name="overview_image_id" id="submission_overview_image_id" value="">
                        <p id="submission_overview_image_id_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group form-group-beschreibung">
                    <label class="col-sm-3 control-label">Nahaufnahme 1*</label>
                    <div class="col-sm-9">
                        <p id="submission_note_symptoms">Laden Sie hier eine Nahaufnahme <b>(aus 10 cm Entfernung)</b> der betroffenen Region hoch.</p>
                        <div id="uploader-closeup"></div>
                        <input type="hidden" name="closeup_image_id" id="submission_closeup_image_id" value="">
                        <p id="submission_closeup_image_id_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group form-group-beschreibung">
                    <label class="col-sm-3 control-label">Nahaufnahme 2*</label>
                    <div class="col-sm-9">
                        <p id="submission_note_symptoms">Laden Sie hier eine weitere Nahaufnahme <b>(aus 10 cm Entfernung)</b> hoch. Diese sollte aus einem anderen Blickwinkel als die erste Nahaufname gemacht worden sein.</p>
                        <div id="uploader-closeup2"></div>
                        <input type="hidden" name="closeup2_image_id" id="submission_closeup2_image_id" value="">
                        <p id="submission_closeup2_image_id_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <h2>2. Beschreibung</h2>
                <div class="form-group form-group-beschreibung">
                    <label class="col-sm-3 control-label" for="submission_email">Beschwerden*</label>
                    <div class="col-sm-9">
                        <p id="submission_note_symptoms">Welche Beschwerde(n) verursacht Ihr Hautproblem?<br>
                            Wählen Sie alle Punkte aus, die auf Ihr Hautproblem zutreffen.
                        </p>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="1">Ausschlag</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="2">Juckreiz</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="3">Schwellung</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="4">Rötung</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="5">Schmerzen</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="6">Schuppung</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="7">Muttermal</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="8">Flecken</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="symptoms[]" value="9" id="symptom_sonstiges">Sonstige</label></div>
                        <p id="submission_symptoms_error" class="submission_field_error" style="display: none"></p>
                        <input type="text" class="form-control" id="submission_other_symptoms" name="other_symptoms" placeholder="Beschreibung Ihrer Beschwerden." style="display: none">
                        <p id="submission_other_symptoms_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group form-group-beschreibung">
                    <label for="submission_side" class="col-sm-3 control-label">Einseitig/Beidseitig*</label>
                    <div class="col-sm-9">
                        <p>Tritt das Problem einseitig oder beidseitig auf?<br>
                            (Nur auf dem rechten Arm würde z.B. einseitig bedeuten, auf beiden Armen würde beidseitig bedeuten.)
                        </p>
                        {{--<select class="form-control" id="submission_side" name="side">--}}
                            {{--<option value="">- bitte angeben -</option>--}}
                            {{--<option value="one">einseitig</option>--}}
                            {{--<option value="both">beidseitig</option>--}}
                            {{--<option value="not_sure">nicht sicher</option>--}}
                        {{--</select>--}}
                        <label class="radio-inline">
                            <input type="radio" name="side" value="einseitig" class="submission_form_radio">einseitig
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="side" value="beidseitig" class="submission_form_radio">beidseitig
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="side" value="nicht sicher" class="submission_form_radio">nicht sicher
                        </label>
                        <p id="submission_side_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group form-group-beschreibung">
                    <label class="col-sm-3 control-label" for="submission_affected_area">Betroffene Region*</label>
                    <div class="col-sm-9">
                        <p>Wo genau tritt Ihr Hautproblem auf? An welcher Stelle Ihres Körpers ist das Problem zu sehen?</p>
                        <textarea class="form-control" id="submission_affected_area" name="affected_area" rows="2"></textarea>
                        <p id="submission_affected_area_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group form-group-beschreibung">
                    <label for="submission_since" class="col-sm-3 control-label">Seit*</label>
                    <div class="col-sm-9">
                        <p>Wie lange leiden Sie bereits unter dem Hautproblem?</p>
                        <select class="form-control" id="submission_since" name="since">
                            <option value="">- bitte angeben -</option>
                            <option>weniger als zwei Tage</option>
                            <option>zwischen 2 bis 6 Tagen</option>
                            <option>zwischen 1 bis 4 Wochen</option>
                            <option>länger als 1 Monat</option>
                            <option>chronisch/permanent</option>
                            <option>andere Angabe</option>
                        </select>
                        <p id="submission_since_error" class="submission_field_error" style="display: none"></p>
                        <input type="text" class="form-control" id="submission_since_other" name="since_other" placeholder="Seit wann genau?" style="display: none;">
                        <p id="submission_since_other_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group form-group-beschreibung">
                    <label for="submission_treated" class="col-sm-3 control-label">Bisher behandelt*</label>
                    <div class="col-sm-9">
                        <p>Haben Sie bereits ein Medikament oder eine Salbe benutzt, um Ihr Hautproblem zu lindern?</p>
                        <label class="radio-inline">
                            <input type="radio" name="treated" value="1" class="submission_form_radio">Ja
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="treated" value="0" class="submission_form_radio">Nein
                        </label>
                        <p id="submission_treated_error" class="submission_field_error" style="display: none"></p>
                        <textarea class="form-control" id="submission_treatment" name="treatment" rows="2" placeholder="Welches Medikament oder welche Salbe haben Sie benutzt?" style="display: none"></textarea>
                        <p id="submission_treatment_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group form-group-beschreibung">
                <label class="col-sm-3 control-label" for="submission_description">Weitere Informationen</label>
                <div class="col-sm-9">
                    <p>Haben Sie weitere Informationen, die für die Bearbeitung Ihres Anliegens wichtig sind?</p>
                    <textarea class="form-control" id="submission_description" name="description" rows="2"></textarea>
                    <p id="submission_description_error" class="submission_field_error" style="display: none"></p>
                </div>
            </div>

                <h2>3. Weitere Details</h2>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <label class="radio-inline">
                            <input type="radio" name="gender" id="submission_gender_f" value="f" class="submission_form_radio"> weiblich
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gender" id="submission_gender_m" value="m" class="submission_form_radio"> männlich
                        </label>
                        <p id="submission_gender_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="submission_age">Alter</label>
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
                                <input type="checkbox" name="agb_accepted" id="submission_agb_accepted" value="true"> Ich akzeptiere die
                                <a href="{{ config('app.MIX_WEB_URL') }}/agb" target="_blank">allgemeinen Geschäftsbedingungen</a> und die
                                <a href="{{ config('app.MIX_WEB_URL') }}/privacy" target="_blank">Datenschutzerklärung</a>.
                            </label>
                            <p id="submission_agb_accepted_error" class="submission_field_error" style="display: none"></p>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-primary btn-lg" id="submission_submit">Weiter</button>
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
                <h2 style="margin-top: 10px">Ihr Fall wurde angelegt.</h2>
                <p>Bitte notieren Sie sich jetzt Ihre Fallnummer:</p>
                <p class="text-center" style="margin: 20px 0"><b class="lead" id="submission_created_submission_id" style="border: 1px solid #2ea3f2; padding: 5px"></b></p>
                <p>Nach dem Bezahlvorgang wird Ihnen die Fallnummer nicht mehr angezeigt !</p>
                <p>Sollte der Bezahlvorgang fehlschlagen, können Sie diesen auch zu einem späteren Zeitpunkt erneut ausführen, indem Sie ihre Fallnummer hier eintragen:
                <a href="https://online-hautarzt.net/fall-ansehen/" target="_blank" style="font-weight: bold">Fall ansehen</a></p>
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
