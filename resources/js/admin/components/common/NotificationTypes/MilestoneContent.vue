<template>
    <div>

        <template v-if="$route.name === 'summary'">
            <div class="summary-time__link"
                 @click="to">
                VIEW
            </div>
        </template>

        <template v-else-if="$route.name === 'messages'">
            <v-btn depressed
                   color="#1875F0"
                   dark
                   class="chat-message__btn-detail"
                   @click="to">
                VIEW DETAILS
            </v-btn>
        </template>

        <template v-else-if="$route.name === 'contract-history'">
            <button class="contract-history__view main-btn main-btn_full-blue hidden-md-and-down"
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
                    milestone_content_edited: 'milestone_content_edited',
                    milestone_content_accepted: 'milestone_content_accepted',
                    milestone_content_rejected: 'milestone_content_rejected',
                },
            }
        },
        props: {
            notification: Object,
        },
        methods: {
            to() {
                if (this.notification.milestone_content.milestone.status === 'in_progress') {

                    return this.$router.push({
                        name: 'current-milestone',
                        params: {guarantee_project_id: this.$route.params.guarantee_project_id}
                    });
                }

                return this.$router.push({
                    name: 'contract-signed',
                    params: {guarantee_project_id: this.$route.params.guarantee_project_id}
                });
            }
        }
    }
</script>