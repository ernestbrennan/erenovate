<template>
    <div>
        <li v-if="invoices.length > 0" class="header-controls__chat-option" @click="toInvoices">
            <div class="header-controls__chat-op-innner">
                <img src="/img/icon/contract_blue.svg" alt="terms">
                <div>Requests</div>
            </div>
        </li>
        <li class="header-controls__chat-option" @click="downloadPdf">
            <div class="header-controls__chat-op-innner">
                <img src="/img/icon/pdf-icon_blue.svg" alt="messages">
                <div>Download PDF</div>
            </div>
        </li>
        <li class="header-controls__chat-option" @click="toDraftHistory">
            <div class="header-controls__chat-op-innner">
                <img src="/img/icon/history_blue.svg" alt="history">
                <div>Contract History</div>
            </div>
        </li>
        <li class="header-controls__chat-option" @click="toMessages">
            <div class="header-controls__chat-op-innner">
                <img src="/img/icon/messages_blue.svg" alt="messages">
                <div>Messages</div>
            </div>
        </li>
        <li class="header-controls__chat-option" @click="toFileHistory">
            <div class="header-controls__chat-op-innner">
                <img src="/img/icon/files_blue.svg" alt="terms">
                <div>Files History</div>
            </div>
        </li>
        <a href="mailto:tom@erenovate.com">
            <li class="header-controls__chat-option">
                <div class="header-controls__chat-op-innner">
                    <img src="/img/icon/support_blue.svg" alt="history">
                    <div>Support</div>
                </div>
            </li>
        </a>
    </div>

</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        computed:{
            ...mapGetters(["guarantee_project","invoices", "contact", "contract_draft"]),
        },
        methods:{
            toInvoices() {
                this.$router.push({
                    name: 'list-invoice',
                    params: {
                        guarantee_project_id: this.guarantee_project.id
                    }
                })
            },
            toDraftHistory() {
                this.$router.push({
                    name: 'contract-history',
                    params: {
                        guarantee_project_id: this.guarantee_project.id
                    }
                })
            },
            toMessages() {
                this.$router.push({
                    name: 'messages',
                    params: {
                        guarantee_project_id: this.guarantee_project.id
                    }
                })
            },
            toFileHistory() {
                this.$router.push({
                    name: 'fileHistory',
                    params: {
                        guarantee_project_id: this.guarantee_project.id
                    }
                })
            },
            toProject() {
                window.open(
                    this.guarantee_project.project_link,
                    '_blank'
                );
            },
            downloadPdf() {

                var params = {
                    contract_draft: this.contract_draft,
                    contract: this.contract
                };

                api
                    .post(
                        'contract-draft.exportPdf',
                        params,
                        {responseType: 'blob'})
                    .then(response => {

                        let a = document.createElement('a');
                        let url = window.URL.createObjectURL(response.data);

                        a.href = url;
                        a.download = decodeURIComponent(response.headers['file-name']);
                        a.click();

                        window.URL.revokeObjectURL(url);
                    });
            },

        }

    }
</script>

<style scoped>

</style>
