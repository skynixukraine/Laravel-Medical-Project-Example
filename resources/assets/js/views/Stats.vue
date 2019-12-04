<template>
    <div class="row">
        <h2>Statistiken</h2>
        <div class="col-md-12" id="stats-page" style="margin-top: 20px; position: relative; padding: 0">
            <div style="z-index: 2">
                <img v-bind:src="photo" class="arzt-photo center-block" style="position: relative; z-index: 2; border: 4px solid white">
                <h2 class="text-center" style="margin-top: 10px; position: relative; z-index: 2;">{{ userdata.title }} {{ userdata.first_name }} {{ userdata.last_name }}</h2>
            </div>
            <div class="row user-stats" style="position: absolute; top: 80px; background-color: white; z-index: 1; width: 100%; margin-left: 0; padding-top: 140px">
                <div class="col-sm-4 stats-block" id="stats-answered-submissions">
                    <div class="glyphicon-ring center-block"><span class="glyphicon glyphicon-pencil glyphicon-bordered"></span></div>
                    <h2 class="text-center">{{ answered_submissions }}</h2>
                    <p class="text-center">Fälle</p>
                    <p class="text-center stats-footer">Beantwortete Fälle insgesamt</p>
                </div>
                <div class="col-sm-4 stats-block" id="stats-average-rating">
                    <div class="glyphicon-ring center-block"><span class="glyphicon glyphicon-star glyphicon-bordered"></span></div>
                    <h2 class="text-center">{{ average_rating }}</h2>
                    <p class="text-center">Sterne</p>
                    <p class="text-center stats-footer">Durchschnitt bei {{ average_rating_number_stars }} Bewertungen.</p>
                </div>
                <div class="col-sm-4 stats-block" id="stats-answered-last-month">
                    <div class="glyphicon-ring center-block"><span class="glyphicon glyphicon-calendar glyphicon-bordered"></span></div>
                    <h2 class="text-center">{{ answered_submissions_last_month }}</h2>
                    <p class="text-center">Fälle</p>
                    <p class="text-center stats-footer">Beantwortete Fälle im letzten Monat</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'stats',
        data() {
            return {
                loading: false,
                photo: '',
                userdata: '',
                answered_submissions: 0,
                answered_submissions_last_month: 0,
                average_rating: '',
                average_rating_number_stars: 0,
            }
        },
        methods: {
            loadProfile() {
                this.loading = true;
                this.$http.get(window.apiUrl + "/api/user/profile", {headers: this.$root.buildHeader() })
                    .then(
                        function (response) {
                            this.loading = false;
                            this.photo = response.data.photo_url;
                            this.userdata = response.data;
                        },
                        function (error) {
                            if (error.status === 401) { this.$root.logout(); }
                        }
                    );
            },
            loadStats() {
                this.$http.get(window.apiUrl + "/api/user/stats", {headers: this.$root.buildHeader() })
                    .then(
                        function (response) {
                            this.answered_submissions            = response.data.answered_submissions;
                            this.answered_submissions_last_month = response.data.answered_submissions_last_month;
                            this.average_rating                  = response.data.average_rating;
                            if (this.average_rating === null) this.average_rating = '-';
                            this.average_rating_number_stars     = response.data.average_rating_number_stars;
                        },
                        function (error) {
                            if (error.status === 401) { this.$root.logout(); }
                        }
                    );
            },
        },
        created() {
            this.loadProfile();
            this.loadStats();
        }
    }
</script>