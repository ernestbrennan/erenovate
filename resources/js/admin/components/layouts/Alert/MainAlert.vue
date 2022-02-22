<template>
    <div>
        <template v-if="error">
            <v-snackbar
                    multi-line
                    :timeout="4000"
                    :color="error.type"
                    @input="closeError"
                    :value="true"
                    :top="true"
            >
                {{ error.text }}
                <v-btn
                        color="white"
                        flat
                        @click="closeError"
                >
                    Close
                </v-btn>
            </v-snackbar>
        </template>
    </div>
</template>

<script>
    // error.type = 'success', 'info', 'error'
    export default {
        data() {
            return {}
        },
        computed: {
            error(){
                return this.$store.getters.errorAlert
            }
        },
        methods:{
            closeError(){
                this.$store.dispatch('set',{type: 'errorAlert', data: null})
            },
        },
    }
</script>

<style scoped>

</style>