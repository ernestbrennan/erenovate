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
                {{messengerBtnText(notification.type)}}
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
            activator_type:{
                require : false,
                default : 'button'
            },
            title:{
                type: String
            },
        },
        methods: {
            messengerBtnText(type){
                if(type = 'issue_price_modification_created'){return 'VIEW ISSUE'}
                else{ return'VIEW DETAILS'}
            },
            to(){
                return this.$router.push({
                    name: 'issue',
                    params: {
                        issue_id: this.notification.issue.id,
                        guarantee_project_id: this.$route.params.guarantee_project_id,
                    }
                });
            },
            toMobile(){
                if(window.innerWidth > 768){
                    return false
                } else {
                    this.to()
                }
            }
        }
    }
</script>
