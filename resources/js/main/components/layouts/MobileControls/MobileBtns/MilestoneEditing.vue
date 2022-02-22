<template>
    <div>
        <li class="header-controls__chat-option" @click="toCurrentContract">
            <div class="header-controls__chat-op-innner">
                <img src="/img/icon/terms_blue.svg" alt="terms">
                <div>To the Contract</div>

            </div>
        </li>
        <li class="header-controls__chat-option" @click="toCurrentMilestone">
            <div class="header-controls__chat-op-innner">
                <img src="/img/icon/history_blue.svg" alt="history">
                <div>Current Milestone</div>

            </div>
        </li>
        <li class="header-controls__chat-option" @click="toMessages">
            <div class="header-controls__chat-op-innner">
                <img src="/img/icon/messages_blue.svg" alt="post-job">
                <div>Messages</div>

            </div>
        </li>
        <li class="header-controls__chat-option" @click="toFileHistory">
            <div class="header-controls__chat-op-innner">
                <img src="/img/icon/files_blue.svg" alt="terms">
                <div>Files History</div>

            </div>
        </li>
        <!--<li class="header-controls__chat-option" @click="toProject">-->
            <!--<div class="header-controls__chat-op-innner">-->
                <!--<img src="/img/icon/job-post_blue.svg" alt="history">-->
                <!--<div>Job Post</div>-->

            <!--</div>-->
        <!--</li>-->
    </div>

</template>

<script>
    export default {
        data(){
            return {

            }
        },
        methods:{
            toMessages() {
                this.$router.push({
                    name: 'messages',
                    params: {
                        guarantee_project_id: this.$route.params.guarantee_project_id
                    }
                });
            },
            toCurrentMilestone(){
                this.$router.push({
                    name: 'current-milestone',
                    params: {
                        guarantee_project_id: this.$route.params.guarantee_project_id
                    }
                });
            },
            toFileHistory() {
                let targetId = this.$route.params.guarantee_project_id;
                this.$router.push({
                    name: 'fileHistory',
                    params: {guarantee_project_id: targetId}
                })
            },
            toCurrentContract(){
                this.$router.push({
                    name: 'contract-signed',
                    params: {
                        guarantee_project_id: this.$route.params.guarantee_project_id
                    }
                });
            },
            toProject() {
                window.open(
                    this.guarantee_project.project_link,
                    '_blank'
                );
            },

        },

    }
</script>

<style scoped>

</style>