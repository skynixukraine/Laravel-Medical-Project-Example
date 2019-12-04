<template>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><router-link :to="{ name: 'dashboard' }">Ärzteportal</router-link></a>
            </div>
            <div v-if="loggedIn" id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><router-link :to="{ name: 'open-cases' }">Offene Fälle</router-link></li>
                    <li><router-link :to="{ name: 'my-cases' }">Von mir bearbeitete Fälle</router-link></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><router-link :to="{ name: 'profil' }">Profil</router-link></li>
                            <li><router-link :to="{ name: 'stats' }">Statistiken</router-link></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#" @click.prevent="logout">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
</template>

<script>
    export default {
        name: "navigation",
        props: ['loggedIn'],
        methods: {
            logout() {
                this.$emit('logout');
            }
        }
    }
</script>