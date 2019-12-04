<template>
    <form class="form-horizontal" v-on:submit.prevent="login" style="margin-top: 40px">
        <div class="form-group">
            <label class="control-label col-sm-2" for="login_email">Email:</label>
            <div class="col-sm-10">
                <input type="text" id="login_email" v-bind:disabled="loading" v-model="email" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="login_password">Passwort:</label>
            <div class="col-sm-10">
                <input type="password" id="login_password" v-bind:disabled="loading" v-model="password" class="form-control">
                <p class="text-danger" v-if="validationError" v-text="validationError"></p>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" v-bind:disabled="loading" class="btn btn-primary" value="Login">
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        data() {
            return {
                loading: false,
                email: '',
                password: '',
                validationError: ''
            }
        },

        methods: {
            login() {
                let data = {
                    client_id: 2,
                    client_secret: process.env.MIX_CLIENT_SECRET,
                    grant_type: 'password',
                    scope: '',
                    username: this.email,
                    password: this.password
                };
                this.loading = true;
                this.$root.setStatusMessage({'text': 'logging in...'});
                this.$http.post(window.apiUrl + "/oauth/token", data)
                    .then(function (response) {

                        let authUser = {};
                        authUser.accessToken  = response.data.access_token;
                        authUser.refreshToken = response.data.refresh_token;
                        authUser.expiresIn    = response.data.expires_in;
                        window.localStorage.setItem('authUser', JSON.stringify(authUser));

                        // make another request to get the username
                        this.$http.get(window.apiUrl + "/api/user/profile", {headers: this.$root.buildHeader() })
                            .then(function (response) {
                                this.loading = false;
                                this.$root.removeStatus();

                                // save name and status also in authUser
                                authUser.gender = response.data.gender;
                                authUser.title = response.data.title;
                                authUser.first_name = response.data.first_name;
                                authUser.last_name = response.data.last_name;
                                authUser.status = response.data.status;
                                window.localStorage.setItem('authUser', JSON.stringify(authUser));

                                // send event with the authUser
                                this.$emit('update-auth-user', authUser);

                                this.$router.push({ name: 'dashboard'});
                            });

                        // maybe it makes sense to remove this information immediately
                        this.password = '';
                    },
                    function(response){
                        this.loading = false;
                        this.$root.removeStatus();
                        let error = response.body.error;
                        this.validationError = (error == 'invalid_credentials')
                            ? 'Email/Passwort falsch oder der Account ist derzeit nicht freigeschaltet.'
                            : error ;
                    })
            }
        }
    }
</script>
