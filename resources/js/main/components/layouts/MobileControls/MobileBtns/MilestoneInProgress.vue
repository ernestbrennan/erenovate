<template>
    <div>
        <li v-if="showView" class="header-controls__chat-option" @click="viewEdits">
            <div class="header-controls__chat-op-innner">
                <img src="/img/icon/pencil_blue.svg" alt="terms">
                <div>View Edit</div>
            </div>
        </li>
        <!-- <li class="header-controls__chat-option" @click="getMilestone" v-if="isMilestoneEdited">
            <div class="header-controls__chat-op-innner">
                <img src="/img/icon/knowledge_blue.svg" alt="terms">
                <div>Back</div>
            </div>
        </li> -->
        <li class="header-controls__chat-option" @click="toDraftHistory">
            <div class="header-controls__chat-op-innner">
                <img src="/img/icon/history_blue.svg" alt="history">
                <div>Project History</div>
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
        <li class="header-controls__chat-option">
            <a href="mailto:tom@erenovate.com">
                <div class="header-controls__chat-op-innner">
                    <img src="/img/icon/support_blue.svg" alt="history">
                    <div>Support</div>
                </div>
            </a>
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
            ...mapGetters(["user", "contract_draft","current_milestone", "guarantee_project"]),
            isMilestoneEdited(){
                return this.current_milestone.status === 'in_progress' && this.current_milestone.milestone_content.status === 'pending'
            },
            showView(){

                return false
            },
        },
        methods: {
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
            getMilestone(){
                this.$store.state.global_loader = true;
                this.$store.state.read_only_milestone = true;

                if (this.$route.name === 'current-milestone') {

                     this.$store.dispatch('getCurrentMilestone', this.guarantee_project_id).then(response => {
                        this.$store.state.global_loader = false;
                    });
                }

                if (this.$route.name === 'by-id-milestone') {

                    this.$store.dispatch('getMilestoneById', this.$route.params.milestone_id).then(response => {
                        this.$store.state.global_loader = false;
                    });
                }
            },
            toMilestoneEdit(){
                this.$router.push({
                    name: 'edited-milestone',
                    params: {
                        guarantee_project_id: this.$route.params.guarantee_project_id,
                        milestone_id: this.current_milestone.id
                    }
                })
            },
            viewEdits() {
                if(this.isMilestoneEdited){
                    this.toMilestoneEdit()
                } else {
                    this.toMaterialsEdit()
                }
            },
            toMaterialsEdit(){
                const container = $('.contract-comp__body'),
                    scrollTo = $('.contract-comp__body .materials-scroll-to');
                if (typeof scrollTo !== 'undefined') {
                    container.animate({
                        scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop() - 100
                    }, 500);
                }
            },
        },
    }
</script>
