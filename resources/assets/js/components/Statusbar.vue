<template>
    <div v-if="status" class="container" id="statusbar" style="position: fixed; width: 400px; right: 40%; z-index: 5">
        <div style="padding: 5px;">
            <div id="statusbar_inner" class="alert" v-bind:class="infoclass">
                <!--<i class="fa fa-spinner fa-spin"></i>-->
                <p id="statusbar_message">
                    {{ status.text }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "statusbar",
        props: ['status'],
        computed: {
            infoclass: function () {
                if (!jQuery.isEmptyObject(this.status)) {
                    return "alert-" + this.status.mode;
                }
                return 'alert-info';
            }
        },
    }
</script>