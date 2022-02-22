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
                    contract_first_accepted: 'contract_first_accepted',
                    contract_both_accepted: 'contract_both_accepted',
                    contract_signed: 'contract_signed',
                    contract_completed: 'contract_completed',
                },
            }
        },
        props: {
            notification: Object,
        },
        methods: {
            to() {

                if (this.notification.type === this.types.contract_completed) {
                    return this.$router.push({
                        name: 'summary',
                        params: {guarantee_project_id: this.$route.params.guarantee_project_id}
                    });
                }

                else if ((this.notification.type === this.types.contract_first_accepted || this.notification.type === this.types.contract_both_accepted) && this.notification.contract.status === 'pending') {
                    return this.$router.push({
                        name: 'current-draft',
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