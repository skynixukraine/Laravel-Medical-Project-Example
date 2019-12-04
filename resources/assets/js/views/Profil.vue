<template>
    <div>
    <h2>Profil</h2>

    <form class="form-horizontal" @submit.prevent="uploadPhoto" @keydown="validation_photo.clear($event.target.name)" enctype="multipart/form-data">
        <div class="form-group">
            <label class="control-label col-sm-4" for="new_photo">Foto:</label>
            <div class="col-sm-8">
                <img v-bind:src="photo" class="arzt-photo">
                <input type="file" id="new_photo" v-on:change="onImageChange" accept="image/*" style="margin-top: 15px">
                <p class="text-danger" v-if="validation_photo.get('photo')" v-text="validation_photo.get('photo')"></p>
                <button type="submit" v-if="photoSelected" class="btn btn-default" v-bind:disabled="loading" style="margin-top: 15px">Foto hochladen</button>
            </div>
        </div>
    </form>
    <hr>
    <form class="form-horizontal" @submit.prevent="updateProfile" @keydown="validation.clear($event.target.name)">
        <div class="form-group">
            <label class="control-label col-sm-4" for="title">Titel:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="title" name="title" v-model="profiledata.title" placeholder="z.B. Dr.">
                <p class="text-danger" v-if="validation.get('title')" v-text="validation.get('title')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="first_name">Vorname*:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="first_name" name="first_name" v-model="profiledata.first_name">
                <p class="text-danger" v-if="validation.get('first_name')" v-text="validation.get('first_name')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="last_name">Nachname*:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="last_name" name="last_name" v-model="profiledata.last_name">
                <p class="text-danger" v-if="validation.get('last_name')" v-text="validation.get('last_name')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="street">Strasse*:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="street" id="street" v-model="profiledata.street" >
                <p class="text-danger" v-if="validation.get('street')" v-text="validation.get('street')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="zip">PLZ*:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="zip" id="zip" v-model="profiledata.zip" >
                <p class="text-danger" v-if="validation.get('zip')" v-text="validation.get('zip')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="city">Stadt*:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="city" id="city" v-model="profiledata.city" >
                <p class="text-danger" v-if="validation.get('city')" v-text="validation.get('city')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="city">Land*:</label>
            <div class="col-sm-8">
                <select id="country" name="country" class="form-control" v-model="profiledata.country">
                    <option value="DE">Deutschland</option>
                    <option value="AT">Österreich</option>
                    <option value="CH">Schweiz</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="email">Email*:</label>
            <div class="col-sm-8">
                <input type="email" class="form-control" name="email" id="email" v-model="profiledata.email" >
                <p class="text-danger" v-if="validation.get('email')" v-text="validation.get('email')"></p>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
                <button type="submit" class="btn btn-default" v-bind:disabled="loading">Daten aktualisieren</button>
            </div>
        </div>
    </form>
    <hr>
    <form class="form-horizontal" @submit.prevent="updatePassword" @keydown="validation_passworddata.clear($event.target.name)">
        <div class="form-group">
            <label class="control-label col-sm-4" for="password">Passwort*:</label>
            <div class="col-sm-8">
                <input type="password" class="form-control" name="password" id="password" v-model="passworddata.password" placeholder="Neues Passwort">
                <p class="text-danger" v-if="validation_passworddata.get('password')" v-text="validation_passworddata.get('password')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="password_confirmation">Passwort wiederholen*:</label>
            <div class="col-sm-8">
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" v-model="passworddata.password_confirmation" placeholder="Neue Passwort wiederholen">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
                <button type="submit" class="btn btn-default" v-bind:disabled="loading">Passwort ändern</button>
            </div>
        </div>
    </form>
    </div>
</template>

<script>
    import {Errors} from "../app";

    export default {
        name: 'profil',
        data() {
            return {
                profiledata: {},
                passworddata: {},
                loading: false,
                validation: new Errors(),
                validation_passworddata: new Errors(),
                validation_photo: new Errors(),
                photo: '',
                photoSelected: false
            }
        },
        methods: {
            loadProfile() {
                this.loading = true;
                this.$http.get(window.apiUrl + "/api/user/profile", {headers: this.$root.buildHeader() })
                    .then(
                        function (response) {
                            this.loading = false;
                            this.profiledata = response.data;
                            // this.profiledata.title = response.data.title;
                            // this.profiledata.first_name = response.data.first_name;
                            // this.profiledata.last_name = response.data.last_name;
                            // this.profiledata.street = response.data.street;
                            // this.profiledata.zip = response.data.zip;
                            // this.profiledata.city = response.data.city;
                            // this.profiledata.country = response.data.country;
                            // this.profiledata.email = response.data.email;
                            this.photo = response.data.photo_url;
                        },
                        function (error) {
                            if (error.status === 401) { this.$root.logout(); }
                        }
                    );
            },
            updateProfile() {
                this.loading = true;
                this.$http.put(window.apiUrl + "/api/user", this.$data.profiledata, {headers: this.$root.buildHeader()})
                    .then(function (response) {
                            this.loading = false;

                            // status message
                            this.$root.setStatusMessage({ text: 'Profil aktualisiert', timeout: 2000 });

                            // update name of authUser in localStorage
                            let authUser = JSON.parse(window.localStorage.getItem('authUser'));
                            authUser.name = this.profiledata.name;
                            window.localStorage.setItem('authUser', JSON.stringify(authUser));

                            // let the main application reload the authUser from localStorage
                            this.$root.setAuthUserFromLocalStorage();
                        },
                        function(error){
                            if (error.status === 401) { this.$root.logout(); }
                            this.loading = false;
                            this.$root.setStatusMessage({ text: 'Bitte prüfen Sie Ihre Angaben', mode: 'danger', timeout: 2000 });
                            this.validation.record(error.data.errors);
                        })
            },
            updatePassword() {
                this.loading = true;
                this.$http.put(window.apiUrl + "/api/user/password", this.$data.passworddata, {headers: this.$root.buildHeader()})
                    .then(function (response) {
                            this.loading = false;
                            // status message
                            this.$root.setStatusMessage({ text: 'Passwort geändert', timeout: 2500 });
                            // clear Password fields
                            this.$data.passworddata.password = '';
                            this.$data.passworddata.password_confirmation = '';
                            // clear any possibly remaining validation messages
                            this.$data.validation_passworddata.clearAll();
                        },
                        function(error){
                            if (error.status === 401) { this.$root.logout(); }
                            this.loading = false;
                            this.$root.setStatusMessage({ text: 'Bitte prüfen Sie Ihre Angaben', mode: 'danger', timeout: 2000 });
                            this.validation_passworddata.record(error.data.errors);
                        })
            },
            onImageChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length) return;
                this.photoSelected = true;
                this.createPhoto(files[0]);
            },
            createPhoto(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.photo = e.target.result;
                };
                reader.readAsDataURL(file);
                // this.uploadPhoto();
            },
            uploadPhoto() {
                this.loading = true;
                this.$http.post(window.apiUrl + "/api/user/photo", {photo: this.photo}, {headers: this.$root.buildHeader()})
                    .then(function (response) {
                        this.loading = false;
                        this.photoSelected = false;
                        // status message
                        this.$root.setStatusMessage({ text: 'Foto aktualisiert', timeout: 2000 });
                        this.loadProfile();
                    },
                    function(error){
                        if (error.status === 401) { this.$root.logout(); }
                        this.loading = false;
                        this.$root.setStatusMessage({ text: 'Bitte prüfen Sie das hochgeladene Foto', mode: 'danger', timeout: 2000 });
                        this.validation_photo.record(error.data.errors);
                        this.photoSelected = false;
                    })
            }
        },
        created() {
            this.loadProfile();
        }
    }
</script>