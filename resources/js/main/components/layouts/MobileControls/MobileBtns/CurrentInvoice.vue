<template>
    <div>
        <template v-if="user.role === 'contractor' && invoice.status === 'pending' || invoice.status === 'unverified'">
            <li class="header-controls__chat-option" @click="confirmInvoice">
                <div class="header-controls__chat-op-innner">
                    <img src="/img/icon/double-checkmarks_blue.svg" alt="messages">
                    <div>Confirm & Verify</div>
                </div>
            </li>
            <!--<li class="header-controls__chat-option" @click="toCreateInvoice">-->
                <!--<div class="header-controls__chat-op-innner">-->
                    <!--<img src="/img/icon/pencil_blue.svg" alt="messages">-->
                    <!--<div>Edit Invoice</div>-->
                <!--</div>-->
            <!--</li>-->
            <li class="header-controls__chat-option" @click="rejectInvoice">
                <div class="header-controls__chat-op-innner">
                    <img src="/img/icon/close_blue.svg" alt="messages">
                    <div>Decline Invoice</div>
                </div>
            </li>
        </template>
        <li class="header-controls__chat-option" @click="exportInvoice">
            <div class="header-controls__chat-op-innner">
                <img src="/img/icon/pdf-icon_blue.svg" alt="messages">
                <div>Export to PDF</div>
            </div>
        </li>
        <!--<li class="header-controls__chat-option">
            <div class="header-controls__chat-op-innner">
                <img src="/img/icon/knowledge_blue.svg" alt="terms">
                <div>Knowlage Base</div>
            </div>
        </li>-->
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
    import export_invoice from '../../../mixins/export/invoice.js'
    export default {
        mixins: [export_invoice],
        data(){
            return {
                conformation: 'wait'
            }
        },
        computed:{
            ...mapGetters(['user', 'guarantee_project','invoice']),
        },
        methods:{
            toCreateInvoice(){
                this.$store.state.slideSearchContracts = false;
                this.$router.push({
                    name: 'create-invoice',
                    params: {
                        guarantee_project_id: this.$route.params.guarantee_project_id
                    }
                })
            },
            confirmInvoice(){
                this.$store.commit('set', {type: 'invoiceCurrent', data: {dialogComplete: true}})
            },
            rejectInvoice(){
                this.$store.commit('set', {type: 'invoiceCurrent', data: {dialogReject: true}})
            },
        },
    }
</script>
