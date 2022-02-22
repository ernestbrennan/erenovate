<template>
    <div class="chat-message__row chat-message__system" :class="classType">
        <div class="chat-message__avatar">
            <img :src="'/img/icon/bell_white.svg'" alt="avatar">
        </div>
        <div class="chat-message__text-box">
            <h4>
                {{ message.notification.title}}
            </h4>
            <h5>{{ message.created_at | moment("MMM D h:mm:ss A") }} By {{ user.firstname }}</h5>
            <p>{{message.content}}</p>

            <template v-if="checkType('contract')">
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

            <template v-if="message.notification.type === 'invoice_confirmed'">
                <div class="table-data__wr">
                    <div :key="index" v-for="(file, index) in message.files" class="table-files">
                        <div class="table-files__name-size-row">
                            <div class="table-files__icon-name">
                                <div class="img-position"><img src="/img/icon/chat-file.svg" alt="file"></div>
                                {{ file.name }}
                            </div>
                            <div class="table-files__sizes">
                                <span v-html="formatSize(file.size)"></span>
                            </div>
                        </div>
                        <div class="table-files__link">
                            <a @click.prevent="download(file.id)" v-html="downloadLink"></a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
<script>
    import Invoice from './../../../../common/NotificationTypes/Invoice';
    import Contract from './../../../../common/NotificationTypes/Contract';
    import ContractDraft from './../../../../common/NotificationTypes/ContractDraft';
    import MilestoneContent from './../../../../common/NotificationTypes/MilestoneContent';

    import {NotificationTypes} from '../../../../common/Mixins/notification_types'
    import {mapGetters} from 'vuex'
    import dropzone from '../../../../../components/mixins/dropzone/dropzone'

    export default {
        mixins: [NotificationTypes, dropzone],
        components: {
            'contract-type': Contract,
            'contract-draft-type': ContractDraft,
            'milestone-content-type': MilestoneContent,
            'invoice-type': Invoice,
        },
        props: [
            'message',
            'user',
        ],

        data() {
            return {
                guarantee_project_id: this.$route.params.guarantee_project_id,
            }
        },
        computed: {
            downloadLink() {
                return (window.innerWidth >= 768) ? 'DOWNLOAD' : `<img src="/img/icon/download-mobile-chat_blue.svg" alt="">`
            },
            ...mapGetters(["guarantee_project"])
        }
    }
</script>


