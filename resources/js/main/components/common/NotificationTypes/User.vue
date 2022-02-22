<template>
    <div>
        <template v-if="$route.name === 'summary'">
            <template v-if="activator_type === 'button'">
                <div class="summary-time__link"
                     @click="to">
                    VIEW
                </div>
            </template>
            <template>
                <div class="summary-time__title" @click="toMobile">
                    {{ title }}
                </div>
            </template>
        </template>

        <template v-else-if="$route.name === 'messages'">
            <v-btn depressed
                   color="#1875F0"
                   dark
                   class="chat-message__btn-detail"
                   @click="to">
                VIEW PROJECT SCOPE
            </v-btn>
        </template>

        <template v-else-if="$route.name === 'contract-history'">
            <button class="contract-history__view main-btn main-btn_full-blue"
                    @click="to">
                VIEW
            </button>
        </template>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                types: {
                    user_invite: 'user_invite',
                },
            }
        },
        props: {
            notification: Object,
        },
        methods: {

            to(){
                return this.$router.push({
                    name: 'current-draft',
                    params: {guarantee_project_id: this.$route.params.guarantee_project_id}
                });
            }
        },
    }
</script>
