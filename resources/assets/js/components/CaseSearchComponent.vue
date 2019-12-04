<template>
    <div class="navbar-form navbar-right">
        <form v-on:submit.prevent="searchForSubmission">
            <div class="form-group">
                <input type="text" v-model="submission_id" placeholder="Mein Case" class="form-control">
            </div>
            <input type="submit" class="btn btn-success" value="Anzeigen">
        </form>
        <p class="alert alert-danger" v-if="caseSearchError" style="position: absolute; margin-top: 10px">Kein Case gefunden.</p>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                submission_id: '',
                submission: null,
                caseSearchError: null
            }
        },

        methods: {
            searchForSubmission() {
                this.$http.get(this.$root.apiUrl + "/api/submissions/" + this.submission_id)
                    .then(
                        function (response) {
                            this.submission = response.data;
                            this.caseSearchError = null;
                        },
                        function(error){
                            this.caseSearchError = error.body.message; // todo: this is not correct. We need to send a json error
                        }
                    )
            }
        }
    }
</script>
