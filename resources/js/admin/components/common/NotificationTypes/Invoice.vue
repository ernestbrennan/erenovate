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
                    invoice_created: 'invoice_created',
                    invoice_unverified: 'invoice_unverified',
                    invoice_confirmed: 'invoice_confirmed',
                    invoice_completed: 'invoice_completed',
                    invoice_rejected: 'invoice_rejected',
                    invoice_overdue: 'invoice_overdue',
                },
            }
        },
        props: {
            notification: Object,
        },
        methods: {
            to(){
                if (this.notification.invoice.status !== 'completed') {
                     return this.$router.push({
                        name: 'current-invoice',
                        params: {
                            guarantee_project_id: this.$route.params.guarantee_project_id,
                        }
                    });
                }
                return this.$router.push({
                    name: 'history-invoice',
                    params: {
                        invoice_id: this.notification.invoice.id,
                        guarantee_project_id: this.$route.params.guarantee_project_id,
                    }
                });
            }
        }
    }
</script>
