<template>
    <div class="contract-history__element" :class="classType">

        <div class="contract-history__point-wr">
            <div class="contract-history__point" :class="classType">
                <img :src="'/img/icon/' + iconNotification(message.notification)" alt="">
            </div>
        </div>

        <div class="contract-history__message-box">
            <div class="contract-history__message">
                <div class="contract-history__message-title-row">
                    <div class="contract-history__message-title">
                        {{ message.notification.title}}
                    </div>
                    <v-spacer></v-spacer>
                    <div class="hidden-md-and-down">
                        <template v-if=" checkType('contract')">
                            <contract-type :notification="message.notification"/>
                        </template>

                        <template v-else-if="checkType('contract_draft') && guarantee_project.contract_status === 'pending'">
                            <contract-draft-type :notification="message.notification"/>
                        </template>

                        <template v-else-if="checkType('milestone_content')">
                            <milestone-content-type :notification="message.notification"/>
                        </template>

                        <template v-else-if="checkType('invoice')">
                            <invoice-type :notification="message.notification"/>
                        </template>

                        <template v-else-if="checkType('user')">
                            <user-type :notification="message.notification"/>
                        </template>
                    </div>
                </div>
                <div class="contract-history__message-info">
                    <img :src="message.sender.photo" alt="">
                    {{message.sender.firstname}} {{message.sender.lastname}},
                    {{ message.created_at | moment("MMM D h:mm:ss A")}}
                </div>
                <p class="contract-history__message-text">
                    {{message.content }}
                </p>
                <div class=hidden-md-and-up>
                    <template v-if=" checkType('contract')">
                        <contract-type :notification="message.notification"/>
                    </template>

                    <template v-else-if="checkType('contract_draft') && guarantee_project.contract_status === 'pending'">
                        <contract-draft-type :notification="message.notification"/>
                    </template>

                    <template v-else-if="checkType('milestone_content')">
                        <milestone-content-type :notification="message.notification"/>
                    </template>

                    <template v-else-if="checkType('invoice')">
                        <invoice-type :notification="message.notification"/>
                    </template>

                    <template v-else-if="checkType('user')">
                        <user-type :notification="message.notification"/>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import User from './../../common/NotificationTypes/User';
    import Invoice from './../../common/NotificationTypes/Invoice';
    import Contract from './../../common/NotificationTypes/Contract';
    import ContractDraft from './../../common/NotificationTypes/ContractDraft';
    import MilestoneContent from './../../common/NotificationTypes/MilestoneContent';

    import {NotificationTypes} from '../../common/Mixins/notification_types'
    import {IconByNotification} from '../../common/Mixins/notification_types'
    import {mapGetters} from 'vuex'

    export default {
        mixins:[NotificationTypes, IconByNotification],
        components: {
            'contract-type': Contract,
            'contract-draft-type': ContractDraft,
            'milestone-content-type': MilestoneContent,
            'invoice-type': Invoice,
            'user-type': User,
        },
        props: {
            message: Object
        },
        data() {
            return {
                guarantee_project_id: this.$route.params.guarantee_project_id,
            }
        },
        computed: {
            classType() {
                const type = this.message.notification.type;

                if (type === 'contract_draft_offered' ||
                    type === 'contract_accepted' ||
                    type === 'contract_signed' ||
                    type === 'contract_completed' ||
                    type === 'invoice_confirmed' ||
                    type === 'invoice_completed' ||
                    type === 'milestone_content_accepted' ||
                    type === 'invoice_created'
                ) {
                    return 'created'
                }

                else if (type === 'milestone_content_edited' ||
                    type === 'contract_draft_proposed'
                ) {
                    return 'edited'
                }

                else if (type === 'invoice_unverified' ||
                    type === 'contract_draft_rejected' ||
                    type === 'invoice_rejected' ||
                    type === 'milestone_content_rejected' ||
                    type === 'invoice_overdue'
                ) {
                    return 'rejected'
                }
            },

            ...mapGetters(["guarantee_project"])
        },
        methods: {
            toDraftHistory() {

            }
        }
    }
</script>
