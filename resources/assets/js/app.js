
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
require('vue-resource');
// require('vue-router');

// ************* Components ****************
Vue.component('register-component', require('./components/RegisterComponent.vue'));
Vue.component('login-component', require('./components/LoginComponent.vue'));
Vue.component('navigation', require('./components/Navigation.vue'));
Vue.component('statusbar', require('./components/Statusbar.vue'));

// ************** Routes ********************
import VueRouter from 'vue-router';
Vue.use(VueRouter);
import home_view        from './views/Home.vue';
import dashboard_view   from './views/Dashboard.vue';
import profil_view      from './views/Profil.vue';
import stats_view       from './views/Stats.vue';
import open_cases_view  from './views/OpenCases.vue';
import my_cases_view    from './views/MyCases.vue';
const routes = [
    { path: '/',           component: home_view,       name: 'home' }, // includes components for login and register
    { path: '/dashboard',  component: dashboard_view,  name: 'dashboard',  required_auth: true },
    { path: '/profil',     component: profil_view,     name: 'profil',     required_auth: true },
    { path: '/stats',      component: stats_view,      name: 'stats',      required_auth: true },
    { path: '/open-cases', component: open_cases_view, name: 'open-cases', required_auth: true },
    { path: '/my-cases',   component: my_cases_view,   name: 'my-cases',   required_auth: true }
];
const router = new VueRouter({
    routes: routes,
    // mode: 'history'
});
router.beforeEach((to, from, next) => {
    router.options.routes.forEach((route) => {
        if (to.matched[0].path === route.path && route.required_auth) {
            // unfortunately we have to duplicate setAuthUser() and loggedIn from below
            let authUser = JSON.parse(window.localStorage.getItem('authUser'));
            if (!authUser) {
                next({
                    path: '/',
                    query: {
                        redirect: to.fullPath,
                    }
                })
            }
        }
    });
    // Proceed as normal
    next();
});

// todo: move Errors class to its own file
export class Errors {
    constructor() {
        this.errors = {};
    }

    /**
     * Determine if an errors exists for the given field.
     *
     * @param {string} field
     */
    has(field) {
        // return this.errors.hasOwnProperty(field); <-- this also will work
        return _.has(this.errors, field);
    }

    /**
     * Determine if we have any errors.
     */
    any() {
        // console.log(Object.keys(this.errors).length > 0);
        return Object.keys(this.errors).length > 0;
    }

    /**
     * Retrieve the error message for a field.
     *
     * @param {string} field
     */
    get(field) {
        // console.log(_.has(this.errors.errors, field));
        if (_.has(this.errors, field)) {
            // console.log(this.errors['errors'][field][0]);
            return this.errors[field][0];
        }
    }

    /**
     * Record the new errors.
     *
     * @param {object} errors
     */
    record(errors) {
        this.errors = errors;
    }

    /**
     * Clear an error fields.
     *
     * @param {string} field
     */
    clear(field) {
        if (field && _.has(this.errors, field)) {
            delete this.errors[field];
        }
    }

    clearAll() {
        delete this.errors;
    }
}

// we declare these variables globally for easy access from every component
window.apiUrl    = process.env.MIX_API_URL;       // https://api.online-hautarzt.net (use this also for oauth routes!)
window.webUrl    = process.env.MIX_WEB_URL;       // https://www.online-hautarzt.net
window.portalUrl = process.env.MIX_PORTAL_URL;    // https://aerzte.online-hautarzt.net
window.aerztephotosFolder = process.env.MIX_AERZTEPHOTOS_FOLDER;  // https://s3.eu-central-1.amazonaws.com/ohn-public/aerzte

const app = new Vue({
    el: '#app',
    router: router,
    data: {
        authUser: null,
        status: null
    },
    methods: {
        buildHeader() {
            let authUser = JSON.parse(window.localStorage.getItem('authUser'));
            return {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + authUser.accessToken
            };
        },
        setAuthUserFromLocalStorage() {
            this.$data.authUser = JSON.parse(window.localStorage.getItem('authUser'));
            if (this.$data.authUser) {
                this.sendRefreshToken(this.authUser.refreshToken);
            }
        },
        sendRefreshToken(refreshToken) {
            let nextCall = parseInt(this.authUser.expiresIn) * 1000 - 10000; // the next call must be 10s before the token expires
            let data = {
                client_id: 2,
                client_secret: process.env.MIX_CLIENT_SECRET,
                grant_type: 'refresh_token',
                scope: '',
                refresh_token: refreshToken
            };
            let that = this;
            this.$http.post(window.apiUrl + "/oauth/token", data)
                .then(
                    function (response) {
                        // update in local storage
                        let authUser = JSON.parse(window.localStorage.getItem('authUser'));
                        authUser.accessToken  = response.data.access_token;
                        authUser.refreshToken = response.data.refresh_token;
                        authUser.expiresIn    = response.data.expires_in;
                        window.localStorage.setItem('authUser', JSON.stringify(authUser));
                        // update vue data
                        this.$data.authUser.accessToken = authUser.accessToken;
                        this.$data.authUser.refreshToken = authUser.refreshToken;
                        this.$data.authUser.expiresIn = authUser.expiresIn;

                        setTimeout(function () {
                            that.sendRefreshToken(response.data.refresh_token)
                        }, nextCall);
                    },
                    function (error) {
                        if (error.status === 401) { this.logout(); }
                    }
                );
        },
        logout() {
            this.setStatusMessage({ text: 'logging out ...'});
            this.$http.get(window.apiUrl + "/api/logout", {headers: this.buildHeader() })
                .then(function (response) { this.removeStatus(); }, // We dont care if the request failed or not
                      function(response) { this.removeStatus(); }); // We dont care if the request failed or not
            window.localStorage.removeItem('authUser');
            this.$data.authUser = null;
            this.$router.push({ name: 'home' });
        },
        setStatusMessage(obj) {
            this.status = {};
            this.status.text = obj.text;
            this.status.mode = (obj.mode) ? obj.mode : 'info' ;
            if (obj.timeout) {
                setTimeout(function () {
                    this.status = null;
                }.bind(this), obj.timeout);
            }
        },
        removeStatus() {
            this.status = null;
        }
    },
    computed: {
        loggedIn: function () {
            return !jQuery.isEmptyObject(this.authUser);
        },
        salutationAuthUser: function () {
            if (!this.$data.authUser) return '';
            let salutation;
            if (this.$data.authUser.gender === "m") salutation = "Herr ";
            else salutation = "Frau ";
            if (this.$data.authUser.title) salutation += this.$data.authUser.title + " ";
            salutation += this.$data.authUser.last_name;
            return salutation;
        }
    },
    created() {
        this.setAuthUserFromLocalStorage();
    }
});