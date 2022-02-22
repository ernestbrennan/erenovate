<template>
    <div>
        <template v-if="contract_draft.status === 'pending'  || contract_draft.status === 'accepted'">
            <li class="header-controls__chat-option">
                <div class="header-controls__chat-op-innner"
                     @click="proposeTerms"
                >
                    <img src="/img/icon/close_red.svg" alt="terms">
                    <div>REJECT</div>
                </div>
            </li>
        </template>
        <li class="header-controls__chat-option">
            <div class="header-controls__chat-op-innner"
                 @click="proposeTerms"
            >
                <img src="/img/icon/terms_blue.svg" alt="terms">
                <div>Different Terms</div>
            </div>
        </li>
        <li class="header-controls__chat-option">
            <div class="header-controls__chat-op-innner">
                <img src="/img/icon/history_blue.svg" alt="history">
                <div>Draft History</div>
            </div>
        </li>
        <li class="header-controls__chat-option">
            <div class="header-controls__chat-op-innner">
                <img src="/img/icon/job-post_blue.svg" alt="post-job">
                <div>View Job Post</div>
            </div>
        </li>
        <li class="header-controls__chat-option"
            @click="toMessages"
        >
            <div class="header-controls__chat-op-innner">
                <img src="/img/icon/messages_blue.svg" alt="messages">
                <div>Messages</div>
            </div>
        </li>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        data() {
            return {}
        },
        computed: {
            ...mapGetters(["user", "contract_draft", "guarantee_project", "contract"]),
        },
        methods: {
            toMessages() {
                this.$store.state.slideSearchContracts = false;
                this.$router.push({
                    name: 'messages',
                    params: {guarantee_project_id: this.$store.state.contract.guarantee_project_id}
                })
            },
            proposeTerms() {
                this.$store.state.slideSearchContracts = false;
                if (this.contract.interlocutor_info.status !== 'new' && this.contract.interlocutor_info.status === 'pending') {
                    alert('Confirm User Info');
                    return;
                }
                this.$store.state.proposeContract = true;
                this.$store.commit('set', {type: 'readOnly', data: false});
            },

        },
    }
</script>

<style scoped>

</style>