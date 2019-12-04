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

        <h2>Meine bearbeiteten Fälle ({{ submissions.length }})</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="list-group" id="my-cases-list">
                    <a v-for="submission in submissions" @click.prevent="loadDetails(submission.id)" href="#" class="list-group-item" v-bind:class="{ active: submission.id == selectedSubmission}">
                        <h4 class="list-group-item-heading">{{ submission.gender }},  {{ submission.age }}</h4>
                        <span v-if="submission.stars"
                              class="badge"
                              v-bind:class="{ danger: submission.stars < 3, warning: submission.stars === 3 , success: submission.stars > 3 }"
                        >{{ submission.stars }}</span>
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
                                    <div class="questions" v-if="submissionDetails.questions.length > 0">
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
                                    <p v-else class="meta">Sie haben keine Rückfrage gestellt.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row_befund">
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Mein abschließender Befund</div>
                                <div class="panel-body">
                                    <blockquote>
                                        <p v-html="submissionDetails.answer.replace(/\n/g,'<br>')"></p>
                                        <footer>
                                            <span>{{ answeredAtFormatted }}</span>
                                        </footer>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row_bewertung">
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Bewertung durch Patienten</div>
                                <div v-if="submissionDetails.stars" class="panel-body">
                                    <p>
                                        <span>Sterne:</span>
                                        <span v-for="n in submissionDetails.stars" class="glyphicon glyphicon-star bewertung_star" aria-hidden="true"></span>
                                    </p>
                                    <blockquote>
                                        <p v-html="submissionDetails.feedback.replace(/\n/g,'<br>')"></p>
                                    </blockquote>
                                </div>
                                <div v-else class="panel-body">
                                    <p class="meta">Es wurde bisher keine Bewerung abgegeben.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p v-if="submissions.length">
                        Wählen Sie einen bereits bearbeiteten Fall aus der Liste aus, um nochmal alle Angaben zu sehen.
                    </p>
                    <p v-else>Sie haben bisher noch keinen Fall bearbeitet.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "my-cases",
        data() {
            return {
                submissions: [],
                submissionDetails: null,
                selectedSubmission: null, // is only used to set class="active" for the submission the user has clicked on,
                lightboxPhotoUrl: null,
                lightboxPhotoUrlResized: null,
                lightboxPhotoCaption: null
            }
        },
        methods: {
            loadList() {
                this.$root.setStatusMessage({text: 'lade...'});
                this.$http.get(window.apiUrl + "/api/submissions/answered-by-me", {headers: this.$root.buildHeader()})
                    .then(
                        function (response) {
                            this.submissions = response.data;
                            this.$root.removeStatus();
                        },
                        function (error) {
                            if (error.status === 401) { this.$root.logout(); }
                            this.$root.setStatusMessage({ text: 'Fehler' , mode: 'danger', timeout: 2000});
                        }
                    )
            },
            loadDetails(id) {
                this.selectedSubmission = id;
                this.$root.setStatusMessage({text: 'lade...'});
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
                            this.$root.removeStatus();
                        },
                        function (error) {
                            if (error.status === 401) { this.$root.logout(); }
                            this.$root.setStatusMessage({ text: 'Fall konnte nicht geladen werden' , mode: 'danger', timeout: 2000});
                        }
                    )
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
                    this.lightboxPhotoUrlResized = window.apiUrl + '/api/submission/' + this.submissionDetails.id + '/photo/' + this.submissionDetails.overview2_image_id + '/600';
                    this.lightboxPhotoCaption = 'Nahaufnahme 2';
                }
                $('#lightbox').modal('show');
            },
            mysqlTimeStampToDate(timestamp) {
                //function parses mysql datetime string and returns javascript Date object
                //input has to be in this format: 2007-06-05 15:26:02
                let regex=/^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9]) (?:([0-2][0-9]):([0-5][0-9]):([0-5][0-9]))?$/;
                let parts=timestamp.replace(regex,"$1 $2 $3 $4 $5 $6").split(' ');
                return new Date(parts[0], parts[1]-1, parts[2], parts[3], parts[4], parts[5]);
            }
        },
        computed: {
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
            },
            answeredAtFormatted: function () {
                if (!this.submissionDetails) return null;
                let answeredAt = this.mysqlTimeStampToDate(this.submissionDetails.answered_at);
                return answeredAt.getDate() + '.' + (answeredAt.getMonth()+1) + '.' + answeredAt.getFullYear();
            }
        },
        created() {
            this.loadList();
        }
    }
</script>