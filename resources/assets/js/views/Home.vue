<template>
    <div>
        <div class="col-md-12">
            <h1>Willkommen</h1>
            <p>&nbsp;</p>
        </div>
        <div v-if="$root.loggedIn" class="col-md-12" style="min-height: 200px">
            <h2>Hallo, {{ this.$root.authUser.name }}</h2>
            <p>Sie sind aktuell eingeloggt.</p>
            <p>Hier gehts zur <router-link :to="{ name: 'dashboard' }">Einführungsseite</router-link>.</p>
        </div>
        <div v-else>
            <div class="col-md-6">
                <h2>Login</h2>
                <p>Hier können Sie sich in unser Ärzteportal einloggen, wenn Sie bereits als Dermatologe registriert
                    sind und ihr Account freigeschaltet wurde.</p>
                <login-component @update-auth-user="setAuthUser"></login-component>
            </div>
            <div class="col-md-6">
                <h2>Registrierung</h2>
                <p>Als Dermatologe haben Sie die Möglichkeit sich zu registrieren um anonym erstellte Fälle zu
                    bearbeiten.</p>
                <p>Bitte beachten Sie, dass ihr Account erst durch uns freigeschaltet werden muss.</p>
                <register-component></register-component>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "home",
        methods: {
            // because @update-auth-user="this.$root.setAuthUser" doesnt work
            // we need to define the method here and call the parent method from the code
            setAuthUser() {
                this.$root.setAuthUserFromLocalStorage();
            }
        }
    }
</script>