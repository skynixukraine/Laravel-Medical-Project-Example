<template>
    <div>
        <div id="lightbox" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title" id="myModalLabel">{{ lightboxPhotoCaption }}</h3>
                    </div>
                    <div class="modal-body">
                        <figure>
                            <img v-bind:src="lightboxPhotoUrlResized" class="img-responsive">
                            <figcaption>
                                <p><a v-bind:href="lightboxPhotoUrl" target="_blank">Bild in voller Größe in neuem Tab öffnen</a></p>
                            </figcaption>
                        </figure>
                    </div>
                </div>
            </div>
        </div>
        <div id="confirmReleaseModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Bearbeitung abbrechen</h4>
                    </div>
                    <div class="modal-body">
                        <p>Wollen Sie diesen Fall nicht weiter bearbeiten ?<br>
                        Der Fall wird zur Bearbeitung durch Kollegen freigegeben.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="release()" class="btn btn-danger">Ich möchte diesen Fall doch nicht bearbeiten</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="assignedRemainingTimeWarningModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Noch 3 Minuten für Bearbeitung</h4>
                    </div>
                    <div class="modal-body">
                        <p>Sie haben noch 3 Minuten um den Fall zu beantworten. <br>
                        Nach Ablauf der Zeit wird ihre bisher geschriebene Antwort ohne Rückfrage gelöscht und
                        der Fall wieder freigegeben.</p>
                    </div>
                </div>
            </div>
        </div>

        <h2 style="margin-bottom: 5px">Offene Fälle ({{ submissions.length }})</h2>
        <p>Letzte Aktualisierung: {{ lastReloadFormatted }}</p>
        <div class="row">
            <div class="col-md-3">
                <div class="list-group" id="open-cases-list">
                    <a v-for="submission in submissions" @click.prevent="loadDetails(submission.id)" href="#" class="list-group-item" v-bind:class="{ active: submission.id == selectedSubmission}">
                        <h4 class="list-group-item-heading">{{ submission.gender }},  {{ submission.age }}</h4>
                        <span class="badge" v-if="submission.status === 'assigned'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></span>
                        <span class="badge" v-if="submission.status === 'permanently_assigned'"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></span>
                        <p class="list-group-item-text timeleft" v-bind:class="{ danger: submission.minutesLeft < 0, warning: (submission.minutesLeft > 0 && submission.minutesLeft < 120)  }" v-text="timeleftFormatted(submission.minutesLeft)"></p>
                    </a>
                </div>
            </div>
            <div class="col-md-9">
                <div v-if="submissionDetails" id="my-cases-details">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">Beschreibung durch Patienten</div>
                                <div class="panel-body">
                                    <h5>Beschwerden</h5>
                                    <p>{{ symptomsString }}</p>
                                    <h5>Einseitig/Beidseitig</h5>
                                    <p>{{ submissionDetails.side }}</p>
                                    <h5>Betroffene Region</h5>
                                    <p>{{ submissionDetails.affected_area }}</p>
                                    <h5>Aufgetreten seit</h5>
                                    <p>{{ sinceCombined }}</p>
                                    <h5>Bisher behandelt</h5>
                                    <p>{{ treatmentCombined }}</p>
                                    <h5>Weitere Informationen</h5>
                                    <p v-html="submissionDetails.description.replace(/\n/g,'<br>')"></p>
                                    <h5>Geschlecht</h5>
                                    <p>{{ submissionDetails.gender }}</p>
                                    <h5>Alter</h5>
                                    <p>{{ submissionDetails.age }}</p>
                                    <h5 class="meta">Übermittelt</h5>
                                    <p class="meta">{{ submissionDetails.created_at }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">Fotos</div>
                                <div class="panel-body">
                                    <div id="photo_overview_wrapper" class="photo_wrapper">
                                        <figure>
                                            <a href="#" @click.prevent="showLightbox('overview')">
                                                <img v-bind:src="submissionDetails.overview_image_url" class="img-responsive">
                                            </a>
                                            <figcaption>Übersichtsaufnahme</figcaption>
                                        </figure>
                                    </div>
                                    <div id="photo_closeup_wrapper" class="photo_wrapper">
                                        <figure>
                                            <a href="#" @click.prevent="showLightbox('closeup')">
                                                <img v-bind:src="submissionDetails.closeup_image_url" class="img-responsive">
                                                <figcaption>Nahaufnahme 1</figcaption>
                                            </a>
                                        </figure>
                                    </div>
                                    <div id="photo_closeup2_wrapper" class="photo_wrapper">
                                        <figure>
                                            <a href="#" @click.prevent="showLightbox('closeup2')">
                                                <img v-bind:src="submissionDetails.closeup2_image_url" class="img-responsive">
                                                <figcaption>Nahaufnahme 2</figcaption>
                                            </a>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row_questions">
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Rückfragen</div>
                                <div class="panel-body">
                                    <p v-if="submissionDetails.partner.language==='en'" class="alert alert-warning" role="alert">Achtung: Rückfragen müssen in englischer Sprache erfolgen.</p>
                                    <div v-if="submissionDetails.status !== 'permanently_assigned'" >
                                        <p>Sie können dem Patienten eine Rückfrage zum Fall stellen, falls Sie weitere Information für einen Befund benötigen.
                                        Ihre Rückfragen und deren Antwort durch den Patienten sind nicht für andere Dermatologen sichtbar.
                                        Aus diesem Grund wird der Fall <b>permanent an Sie gebunden und Sie müssen am Ende einen Befund abgeben</b>.</p>
                                    </div>
                                    <form v-if="questionPossible" v-on:submit.prevent="postQuestion">
                                        <div class="form-group">
                                            <textarea class="form-control" rows="3" id="question" v-model="formdataQuestion.question"></textarea>
                                        </div>
                                        <input type="submit" v-bind:disabled="loading" class="btn btn-success" value="Frage senden">
                                    </form>
                                    <div class="questions">
                                        <div class="question_wrapper" v-for="question in submissionDetails.questions">
                                            <div class="question">
                                                <blockquote>
                                                    <p v-html="question.question.replace(/\n/g,'<br>')"></p>
                                                    <footer>Meine Rückfrage, {{ question.created_at }}</footer>
                                                </blockquote>
                                            </div>
                                            <div class="answer" v-if="question.answer">
                                                <blockquote>
                                                    <p v-html="question.answer.replace(/\n/g,'<br>')"></p>
                                                    <footer>Antwort des Patienten, {{ question.answered_at }}</footer>
                                                </blockquote>
                                            </div>
                                            <div class="answer no_answer" v-else>
                                                <p>Bisher keine Antwort vom Patienten</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row row_befund">
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Mein abschliessender Befund</div>
                                <div class="panel-body">
                                    <p v-if="submissionDetails.partner.language==='en'" class="alert alert-warning" role="alert">Achtung: Befund muss in englischer Sprache erfolgen.</p>
                                    <form v-if="(submissionDetails.status === 'assigned' || submissionDetails.status === 'permanently_assigned')"
                                          v-on:submit.prevent="postAnswer">
                                        <div class="form-group">
                                            <textarea class="form-control" rows="10" id="comment" v-model="formdata.answer"></textarea>
                                        </div>
                                        <div class="form-group" style="margin-top: 40px">
                                            <h4>Zusätzliche Angaben für Medical Example (nicht für den Patienten sichtbar)</h4>
                                            <label class="control-label" for="diagnosis_possible">War es Ihnen möglich eine Ferndiagnose mitzuteilen?</label>
                                            <select class="form-control" id="diagnosis_possible" v-model="formdata.diagnosis_possible" required>
                                                <option value="1">Ja</option>
                                                <option value="0">Nein, es war mir keine Ferndiagnose möglich.</option>
                                            </select>
                                        </div>
                                        <div class="form-group" v-if="formdata.diagnosis_possible === '1'">
                                            <label class="control-label" for="diagnosis">Meine Ferndiagnose lautet (z.B. "Basalzellkarzinom")</label>
                                            <input type="text" class="form-control" id="diagnosis" v-model="formdata.diagnosis" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="requires_doctors_visit">Muss der Patient trotz Ihrer Antwort in die Klinik bzw. in die Praxis?</label>
                                            <select class="form-control" id="requires_doctors_visit" v-model="formdata.requires_doctors_visit" required>
                                                <option value="1">Ja</option>
                                                <option value="0">Nein</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="did_recommend_medicine">Haben Sie eine frei verkäufliche Salbe und/oder Medikament empfohlen?</label>
                                            <select class="form-control" id="did_recommend_medicine" v-model="formdata.did_recommend_medicine" required>
                                                <option value="1">Ja</option>
                                                <option value="0">Nein</option>
                                            </select>
                                        </div>
                                        <div class="form-group" v-if="formdata.did_recommend_medicine === '1'">
                                            <label class="control-label" for="recommended_medicine">Welches Medikament und oder Salbe haben Sie empfohlen?</label>
                                            <input type="text" class="form-control" id="recommended_medicine" v-model="formdata.recommended_medicine" required>
                                        </div>
                                        <input type="submit" v-bind:disabled="loading" class="btn btn-success" value="Befund senden">
                                        <input v-if="submissionDetails.status === 'assigned'" type="button" v-bind:disabled="loading" @click="releaseModal()" class="btn btn-danger" value="Abbrechen">
                                        <span v-if="submissionDetails.status === 'assigned'"  id="open-case-timer">
                                            Verbleibende Zeit für Beantwortung:
                                            <span class="minutes-remaining" v-bind:class="{ warning: assignedSubmissionMinutesLeft <= 3 }">{{ assignedSubmissionMinutesLeft }} min</span>
                                        </span>
                                        <span v-else id="permanently-assigned-notice">
                                            Dieser Fall ist Ihnen permanent zugewiesen.
                                        </span>
                                    </form>
                                    <div v-else>
                                        <p>Bitte denken Sie daran, dass Sie einen Fall innerhalb von <b>{{ assignMinutesLimit }}min</b> beantworten müssen, ansonsten wird der Fall wieder freigegeben.<br>
                                            Ausserdem können Sie immer nur einen Fall gleichzeitig bearbeiten.</p>
                                        <button type="button" class="btn btn-primary" @click="assign">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Diese Anfrage bearbeiten
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div v-if="submissions.length">
                        <p>Bitte wählen Sie einen offenen Fall aus der Liste aus.<br>
                            Die Liste aktualisiert sich alle {{ reloadInterval/60 }} min automatisch.</p>
                    </div>
                    <p v-else>
                        Derzeit gibt es keinen offenen Fall.<br>
                        Die Liste aktualisiert sich alle {{ reloadInterval/60 }} min automatisch.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "open-cases",
        data() {
            return {
                submissions: [],
                assignedSubmission: null,
                assignedSubmissionMinutesLeft: null,
                assignedRemainingTimeWarningShown: false,
                reloadInterval: 300, // seconds
                assignMinutesLimit: 60, // how many minutes can a user have a submission assigned
                openCasesNextReload: null,
                openCasesIds: null,
                checkReloadTimer: null,
                lastReload: null,
                submissionDetails: null,
                selectedSubmission: null, // is only used to set class="active" for the submission the user has clicked on,
                lightboxPhotoUrl: null,
                lightboxPhotoUrlResized: null,
                lightboxPhotoCaption: null,
                formdata: {
                    answer: null,
                    diagnosis_possible: null,
                    diagnosis: null,
                    requires_doctors_visit: null,
                    did_recommend_medicine: null,
                    recommended_medicine: null
                },
                formdataQuestion: {
                    question: null
                },
                loading: false
            }
        },
        methods: {
            releaseModal() {
                $('#confirmReleaseModal').modal('show');
            },
            // clicking the button "Ich möchte den Fall doch nicht bearbeiten" calls release()
            release() {
                $('#confirmReleaseModal').modal('hide');
                let url = window.apiUrl + "/api/submission/" + this.submissionDetails.id + "/release";
                this.$http.get(url, {headers: this.$root.buildHeader()})
                    .then(
                        function (response) {
                            this.clearFormData();
                            this.loadDetails(this.submissionDetails.id);
                            this.loadList();
                        },
                        function (error) {
                            if (error.status === 401) { this.$root.logout(); }
                            this.$root.setStatusMessage({ text: error.data.errors.id[0] , mode: 'danger', timeout: 2000});
                        }
                    )
            },
            // When the 30-min-timer runs out, releaseAssigned is automatically called
            // We send /release to the server but we dont care if an error comes back, because the case might
            // have been already released on the server
            releaseAssigned() {
                let url = window.apiUrl + "/api/submission/" + this.assignedSubmission.id + "/release";
                this.$http.get(url, {headers: this.$root.buildHeader()})
                    .then(
                        function (response) {
                        },
                        function (error) {
                            if (error.status === 401) { this.$root.logout(); }
                        }
                    );
                this.clearFormData();
                this.assignedSubmission = null;
                this.assignedSubmissionMinutesLeft = null;
                this.assignedRemainingTimeWarningShown = false;
                this.submissionDetails = null;
                this.loadList();
            },
            timeleftFormatted(minutesLeft) {
                let submission_minutesLeft = parseInt(minutesLeft);
                let submission_hoursLeft = Math.round( submission_minutesLeft / 60);
                let submission_timeleft = '';

                if (submission_minutesLeft >= 0) {
                    submission_timeleft = "verbleibend: " + submission_hoursLeft + " Stunden";
                }
                else {
                    submission_timeleft = "überfällig: " + Math.abs(submission_hoursLeft) + " Stunden";
                }
                return submission_timeleft;
            },
            assign() {
                let url = window.apiUrl + "/api/submission/" + this.submissionDetails.id + "/assign";
                this.$http.get(url, {headers: this.$root.buildHeader()})
                    .then(
                        function (response) {
                            this.loadDetails(this.submissionDetails.id);
                            this.loadList();
                        },
                        function (error) {
                            if (error.status === 401) { this.$root.logout(); }
                            this.$root.setStatusMessage({ text: error.data.errors.id[0] , mode: 'danger', timeout: 2000});
                        }
                    )
            },
            postAnswer() {
                let url = window.apiUrl + "/api/submission/" + this.submissionDetails.id + "/answer";
                this.loading = true;
                this.$root.setStatusMessage({ text: 'Speichere Befund...' });
                this.$http.post(url, this.formdata, {headers: this.$root.buildHeader()})
                    .then(
                        function (response) {
                            this.loading = false;
                            this.$root.setStatusMessage({ text: 'Befund gesendet' , mode: 'success', timeout: 2000});
                            this.clearFormData();
                            this.resetDetails();
                            this.loadList(false);
                        },
                        function (error) {
                            if (error.status === 401) { this.$root.logout(); }
                            this.loading = false;
                            this.$root.setStatusMessage({ text: error.data.errors.id[0] , mode: 'danger', timeout: 2000});
                        }
                    )
            },
            postQuestion() {
                let url = window.apiUrl + "/api/submission/" + this.submissionDetails.id + "/question";
                this.loading = true;
                this.$root.setStatusMessage({ text: 'Sende Rückfrage...' });
                this.$http.post(url, this.formdataQuestion, {headers: this.$root.buildHeader()})
                    .then(
                        function (response) {
                            this.loading = false;
                            this.$root.setStatusMessage({ text: 'Rückfrage gesendet' , mode: 'success', timeout: 2000});
                            this.formdataQuestion.question = null;
                            this.resetDetails();
                            this.loadList(false);
                        },
                        function (error) {
                            if (error.status === 401) { this.$root.logout(); }
                            this.loading = false;
                            this.$root.setStatusMessage({ text: error.data.errors.id[0] , mode: 'danger', timeout: 2000});
                        }
                    )
            },
            notifyAboutNewOpenCases() {
                let oldOpenCasesIds = this.$data.openCasesIds,
                    currentOpenCasesIds = this.submissions.map(function (opencase) { return opencase.id; }),
                    newOpenCasesCount = 0;

                // If openCasesIds does not yet exist in localStorage, we dont want to send a notification
                if (oldOpenCasesIds === null) {
                    return;
                }

                currentOpenCasesIds.forEach(function (currentOpenCasesId) {
                    if (oldOpenCasesIds.indexOf(currentOpenCasesId) === -1) {
                        newOpenCasesCount ++;
                    }
                });
                if (newOpenCasesCount > 0) {
                    if ('Notification' in window) {
                        Notification.requestPermission(function() {
                            let notification = new Notification('Neue Fälle verfügbar', {
                                body: (newOpenCasesCount === 1) ? 'Ein neuer Fall ist soeben zur Bearbeitung eingegangen.'
                                                                : newOpenCasesCount + ' neue Fälle sind soeben zur Bearbeitung eingegangen.',
                                lang: 'de-DE',
                                icon: window.portalUrl + '/images/logo.jpg'
                            });
                        });
                    }
                }
            },
            setAssignedSubmission() {
                let assignedSubmissions = this.submissions.filter(function (submission) {
                    return submission.status === "assigned";
                });
                if (!assignedSubmissions[0]) {
                    this.$data.assignedSubmission = null;
                }
                else {
                    this.$data.assignedSubmission = assignedSubmissions[0];
                    this.updateAssignedSubmissionTimeLeft();
                }
            },
            updateAssignedSubmissionTimeLeft() {
                if (this.$data.assignedSubmission) {
                    let assignedAtTimestamp = new Date(this.$data.assignedSubmission.assigned_at).getTime(),
                        nowTimestamp = Date.now(),
                        secondsAgo = Math.floor(nowTimestamp/1000) - Math.floor(assignedAtTimestamp/1000),
                        minutesAgo = Math.floor(secondsAgo/60),
                        minutesLeft = this.$data.assignMinutesLimit - minutesAgo;
                    this.$data.assignedSubmissionMinutesLeft = minutesLeft;
                    if (minutesLeft === 3 && !this.assignedRemainingTimeWarningShown) {
                        $('#assignedRemainingTimeWarningModal').modal('show');
                        this.assignedRemainingTimeWarningShown = true;
                    }
                    if (minutesLeft <= 0) {
                        this.releaseAssigned();
                    }
                    else {
                        setTimeout(this.updateAssignedSubmissionTimeLeft, 10000);
                    }
                }
                else {
                    this.$data.assignedSubmissionMinutesLeft = null;
                }
            },
            loadList(showStatusMessages=true) {
                this.lastReload = Date.now();
                if (showStatusMessages) this.$root.setStatusMessage({text: 'lade...'});
                this.$http.get(window.apiUrl + "/api/submissions/open", {headers: this.$root.buildHeader()})
                    .then(
                        function (response) {
                            this.submissions = response.data;
                            if (showStatusMessages) this.$root.removeStatus();
                            this.notifyAboutNewOpenCases();
                            this.setAssignedSubmission();
                            this.$data.openCasesIds = this.submissions.map(function (opencase) {
                                return opencase.id;
                            });
                        },
                        function (error) {
                            if (error.status === 401) { this.$root.logout(); }
                            this.$root.setStatusMessage({ text: 'Fehler beim Laden der offenen Fälle' , mode: 'danger', timeout: 2000});
                        }
                    )
            },
            loadDetails(id) {
                this.selectedSubmission = id;
                this.$http.get(window.apiUrl + "/api/submission/" + id, {headers: this.$root.buildHeader()})
                    .then(
                        function (response) {
                            let seit = null;
                            switch (response.data.since) {
                                case "days" : seit = "Tagen"; break;
                                case "weeks" : seit = "Wochen"; break;
                                case "monthes" : seit = "Monaten"; break;
                                case "years" : seit = "Jahren";
                            }
                            response.data.seit = seit;

                            this.submissionDetails = response.data;
                            this.submissionDetails.closeup_image_url  = window.apiUrl + '/api/submission/' + id + '/photo/' + response.data.closeup_image_id + '/600';
                            this.submissionDetails.closeup2_image_url = window.apiUrl + '/api/submission/' + id + '/photo/' + response.data.closeup2_image_id + '/600';
                            this.submissionDetails.overview_image_url = window.apiUrl + '/api/submission/' + id + '/photo/' + response.data.overview_image_id + '/600';
                        },
                        function (error) {
                            if (error.status === 401) { this.$root.logout(); }
                            this.$root.setStatusMessage({ text: 'Fall konnte nicht geladen werden' , mode: 'danger', timeout: 2000});
                        }
                    )
            },
            resetDetails() {
                this.submissionDetails = null;
            },
            showLightbox(imagetype) {
                if (imagetype == "overview") {
                    this.lightboxPhotoUrl = window.apiUrl + '/api/submission/' + this.submissionDetails.id + '/photo/' + this.submissionDetails.overview_image_id;
                    this.lightboxPhotoUrlResized = window.apiUrl + '/api/submission/' + this.submissionDetails.id + '/photo/' + this.submissionDetails.overview_image_id + '/600';
                    this.lightboxPhotoCaption = 'Überblick';
                }
                if (imagetype == "closeup") {
                    this.lightboxPhotoUrl = window.apiUrl + '/api/submission/' + this.submissionDetails.id + '/photo/' + this.submissionDetails.closeup_image_id;
                    this.lightboxPhotoUrlResized = window.apiUrl + '/api/submission/' + this.submissionDetails.id + '/photo/' + this.submissionDetails.closeup_image_id + '/600';
                    this.lightboxPhotoCaption = 'Nahaufnahme 1';
                }
                if (imagetype == "closeup2") {
                    this.lightboxPhotoUrl = window.apiUrl + '/api/submission/' + this.submissionDetails.id + '/photo/' + this.submissionDetails.closeup2_image_id;
                    this.lightboxPhotoUrlResized = window.apiUrl + '/api/submission/' + this.submissionDetails.id + '/photo/' + this.submissionDetails.closeup2_image_id + '/600';
                    this.lightboxPhotoCaption = 'Nahaufnahme 2';
                }
                $('#lightbox').modal('show');
            },
            cancelReload: function() {
                clearTimeout(this.checkReloadTimer);
            },
            setNextReload: function () {
                let nextReload = Math.floor(Date.now()/1000) + this.reloadInterval;
                this.$data.openCasesNextReload = String(nextReload);
            },
            getNextReload: function () {
                let nextReload = this.$data.openCasesNextReload;
                if (Math.floor(Date.now()/1000) >= nextReload) {
                    this.loadList();
                    this.setNextReload();
                }
                this.checkReloadTimer = setTimeout(this.getNextReload, 10000);
            },
            clearFormData: function () {
                this.formdata.answer = null;
                this.formdata.diagnosis_possible = null;
                this.formdata.diagnosis = null;
                this.formdata.requires_doctors_visit = null;
                this.formdata.did_recommend_medicine = null;
                this.formdata.recommended_medicine = null;
            }
        },
        computed: {
            lastReloadFormatted: function() {
                let date = new Date(this.lastReload);
                return date.toLocaleTimeString('de-DE');
            },
            questionPossible: function() {
                if (!this.submissionDetails) return false;
                if (!this.submissionDetails.questions[0]) return true;
                return (this.submissionDetails.questions[0].answered_at);
            },
            sinceCombined: function () {
                if (!this.submissionDetails) return null;
                if (this.submissionDetails.since_other && this.submissionDetails.since_other !== "Bitte näher erläutern:") {
                    return this.submissionDetails.since +  ' (' + this.submissionDetails.since_other + ')';
                }
                else {
                    return this.submissionDetails.since;
                }
            },
            treatmentCombined: function () {
                if (!this.submissionDetails) return null;
                if (this.submissionDetails.treated) {
                    return 'Ja (' + this.submissionDetails.treatment + ')';
                }
                return 'Nein';
            },
            symptomsString: function () {
                if (!this.submissionDetails) return null;

                let symptoms = this.submissionDetails.symptoms,
                    that = this;

                // order symptoms bei "order"
                symptoms = symptoms.sort(function(a,b) {return (a["order"] > b["order"]) ? 1 : ((b["order"] > a["order"]) ? -1 : 0);} );

                symptoms = symptoms.map(function (s) {
                    if (s.name === 'sonstiges') {
                        return s.name + ' (' + that.submissionDetails.other_symptoms + ')';
                    }
                    return s.name;
                });

                return symptoms.join(', ');
            }
        },
        created() {
            this.loadList();
            this.setNextReload();
            this.getNextReload();
        },
        beforeDestroy() {
            this.$data.assignedSubmission = null; // why is this even needed ?
            this.cancelReload();
        }
    }
</script>