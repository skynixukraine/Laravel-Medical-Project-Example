<template>

    <div v-if="registrationComplete">
        <div class="alert alert-info" role="alert">Ihre Registrierung ist eingegangen. Wir werden ihre Bewerbung prüfen
            und uns innerhalb von 24 Stunden bei Ihnen melden.</div>
    </div>

    <form v-else class="form-horizontal" @submit.prevent="register" @keydown="validation.clear($event.target.name)" style="margin-top: 40px">
        <!--<div class="form-group">-->
            <!--<label class="control-label col-sm-4" for="foto">Foto:</label>-->
            <!--<div class="col-sm-8">-->
                <!--<input type="file" class="form-control" id="foto" name="foto" v-model="foto">-->
            <!--</div>-->
        <!--</div>-->
        <div class="form-group">
            <label class="control-label col-sm-4" for="gender">Geschlecht:</label>
            <div class="col-sm-8">
                <select id="gender" name="gender" class="form-control" v-model="formdata.gender">
                    <option value="f">Frau</option>
                    <option value="m">Herr</option>
                </select>
                <p class="text-danger" v-if="validation.get('gender')" v-text="validation.get('gender')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="title">Titel:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="title" name="title" v-model="formdata.title" placeholder="z.B. Dr." >
                <p class="text-danger" v-if="validation.get('title')" v-text="validation.get('title')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="first_name">Vorname*:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="first_name" name="first_name" v-model="formdata.first_name">
                <p class="text-danger" v-if="validation.get('first_name')" v-text="validation.get('first_name')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="last_name">Nachname*:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="last_name" name="last_name" v-model="formdata.last_name">
                <p class="text-danger" v-if="validation.get('last_name')" v-text="validation.get('last_name')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="birthday">Geburtstag*:</label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="birthday" name="birthday" v-model="formdata.birthday">
                <p class="text-danger" v-if="validation.get('birthday')" v-text="validation.get('birthday')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="birthplace">Geburtsort*:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="birthplace" name="birthplace" v-model="formdata.birthplace">
                <p class="text-danger" v-if="validation.get('birthplace')" v-text="validation.get('birthplace')"></p>
            </div>
        </div>
        <div class="form-group">
            <hr>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="street">Strasse*:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="street" id="street" v-model="formdata.street" >
                <p class="text-danger" v-if="validation.get('street')" v-text="validation.get('street')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="zip">PLZ*:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="zip" id="zip" v-model="formdata.zip" >
                <p class="text-danger" v-if="validation.get('zip')" v-text="validation.get('zip')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="city">Ort*:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="city" id="city" v-model="formdata.city" >
                <p class="text-danger" v-if="validation.get('city')" v-text="validation.get('city')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="city">Land*:</label>
            <div class="col-sm-8">
                <select id="country" name="country" class="form-control" v-model="formdata.country">
                    <option value="DE">Deutschland</option>
                    <option value="AT">Österreich</option>
                    <option value="CH">Schweiz</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="phone">Telefon*:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="phone" id="phone" v-model="formdata.phone" >
                <p class="text-danger" v-if="validation.get('phone')" v-text="validation.get('phone')"></p>
            </div>
        </div>
        <div class="form-group">
            <hr>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="graduation_year">Jahr der Facharztprüfung*:</label>
            <div class="col-sm-8">
                <input type="number" class="form-control" name="graduation_year" id="graduation_year" v-model="formdata.graduation_year" placeholder="z.B. 1994">
                <p class="text-danger" v-if="validation.get('graduation_year')" v-text="validation.get('graduation_year')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="reason_for_application">Warum bewerben Sie sich?*:</label>
            <div class="col-sm-8">
                <textarea class="form-control" name="reason_for_application" id="reason_for_application" v-model="formdata.reason_for_application"></textarea>
                <p class="text-danger" v-if="validation.get('reason_for_application')" v-text="validation.get('reason_for_application')"></p>
            </div>
        </div>
        <div class="form-group">
            <hr>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="email">Email*:</label>
            <div class="col-sm-8">
                <input type="email" class="form-control" name="email" id="email" v-model="formdata.email" >
                <p class="text-danger" v-if="validation.get('email')" v-text="validation.get('email')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="password">Passwort*:</label>
            <div class="col-sm-8">
                <input type="password" class="form-control" name="password" id="password" v-model="formdata.password" placeholder="Bitte vergeben Sie ein Passwort">
                <p class="text-danger" v-if="validation.get('password')" v-text="validation.get('password')"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-4" for="password_confirmation">Passwort wiederholen*:</label>
            <div class="col-sm-8">
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" v-model="formdata.password_confirmation" placeholder="Bitte wiederholen Sie das Passwort">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
                <div class="checkbox">
                    <label><input type="checkbox" name="agb_accepted" v-model="formdata.agb_accepted" value="true" @click="validation.clear('agb_accepted')"> Ich erkläre mich mit den
                        <a v-bind:href="urlAerzteAGB" target="_blank">AGB</a> einverstanden</label>
                    <p class="text-danger" v-if="validation.get('agb_accepted')" v-text="validation.get('agb_accepted')"></p>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
                <button type="submit" class="btn btn-default" v-bind:disabled="loading">Senden</button>
            </div>
        </div>
    </form>
</template>

<script>
    import {Errors} from "../app";

    export default {
        data() {
            return {
                urlAerzteAGB: window.webUrl + "/agbportal",
                formdata: {
                    name: '',
                    street: '',
                    city: '',
                    country: 'DE',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    agb_accepted: false
                },
                loading: false,
                validation: new Errors(),
                registrationComplete: false
            }
        },
        methods: {
            register() {
                this.loading = true;
                this.$http.post(window.apiUrl + "/api/register", this.$data.formdata)
                    .then(function (response) {
                            this.loading = false;
                            this.registrationComplete = true;
                        },
                        function(response){
                            this.loading = false;
                            this.validation.record(response.data.errors);
                        })
            }
        }

    }
</script>