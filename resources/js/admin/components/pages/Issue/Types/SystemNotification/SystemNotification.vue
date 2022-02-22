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

            <template v-else-if="checkType('materials_content')">
                <materials-content-type :notification="message.notification"/>
            </template>

            <template v-else-if="checkType('invoice')">
                <invoice-type :notification="message.notification"/>
            </template>

            <template v-if="message.notification.type === 'invoice_confirmed'">
                <div class="table-data__wr">
                    <div :key="index" v-for="(value, index) in message.files" class="table-files">
                        <div class="table-files__name-size-row">
                            <div class="table-files__icon-name">
                                <div class="img-position"><img src="/img/icon/chat-file.svg" alt="file"></div>
                                {{ value.name }}
                            </div>
                            <div class="table-files__sizes">{{ value.size|formatSize }}</div>
                        </div>
                        <div class="table-files__link">
                            <a @click.prevent="download(value.id)" v-html="downloadLink"></a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
<script>
    import Invoice from '../../../../common/NotificationTypes/Invoice';
    import Contract from '../../../../common/NotificationTypes/Contract';
    import ContractDraft from '../../../../common/NotificationTypes/ContractDraft';
    import MilestoneContent from '../../../../common/NotificationTypes/MilestoneContent';
    import MaterialsContent from '../../../../common/NotificationTypes/MaterialsContent';

    import {NotificationTypes} from '../../../../common/Mixins/notification_types'
    import {mapGetters} from 'vuex'

    export default {
        mixins: [NotificationTypes],
        components: {
            'contract-type': Contract,
            'contract-draft-type': ContractDraft,
            'milestone-content-type': MilestoneContent,
            'materials-content-type': MaterialsContent,
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
        filters: {
            formatSize: function (bytes) {
                if (bytes >= 1073741824) {

                    bytes = (bytes / 1073741824).toFixed(2) + " GB";

                } else if (bytes >= 1048576) {

                    bytes = (bytes / 1048576).toFixed(2) + " MB";

                } else if (bytes >= 1024) {

                    bytes = (bytes / 1024).toFixed(2) + " KB";
                }

                return bytes;
            }
        },
        computed: {
            downloadLink() {
                return (window.innerWidth >= 768) ? 'DOWNLOAD' : `<img src="/img/icon/download-mobile-chat_blue.svg" alt="">`
            },
            ...mapGetters(["guarantee_project"])
        },
        methods: {
            download(file_id) {
                api({
                    method: 'post',
                    url: '/api/file.download',
                    data: {
                        file_id: file_id
                    },
                    responseType: 'blob'
                })
                    .then(response => {

                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(response.data);

                        a.href = url;
                        a.download = response.headers['file-name'];
                        a.click();

                        window.URL.revokeObjectURL(url);
                    });

            },
        }
    }
</script>


