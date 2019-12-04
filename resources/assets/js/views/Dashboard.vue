<template>
    <div class="row">
        <div class="col-md-12">
            <h2>Willkommen {{ this.$root.salutationAuthUser }}</h2>
            <p>Unter "Offene Fälle" finden Sie alle derzeit unbearbeiteten Fälle von Nutzern.
                Sie können immer nur einen gleichzeitig bearbeiten.
                Während Sie einen Fall bearbeiten, ist dieser für alle anderen registrierten Dermatologen gesperrt.
            </p>
            <p>Unter "Von mir bearbeitete Fälle" können Sie nochmal alle von Ihnen beantworteten Fälle ansehen.
                Eine nachträgliche Änderung des Befundes ist aber nicht mehr möglich.</p>
        </div>
    </div>
</template>

<script>
    export default {
        name: "dashboard"
    }
</script>