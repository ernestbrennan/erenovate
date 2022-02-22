<template>
    <div class="chat__header hidden-sm-and-down" data-v-step="ms-1">
        <dialog-confirm
                v-model="dialogChatWithdraw"
                :content="dialogContent"
                reverse="true"
                @submit="closeIssue"
        ></dialog-confirm>
        <v-layout row wrap class="messenger-ho-9b">
            <p class="common-p">
                Issue #{{issue ? issue.sequence : 0}}
            </p>
            <v-spacer></v-spacer>

            <div class="chat-control__row" v-if="issue && issue.status !== 'closed'">
                <v-btn flat class="chat-control__btn chat-control__file-history" @click="toProjectSigned">
                    Project Scope
                </v-btn>
                <v-btn flat class="chat-control__btn chat-control__withdraw" @click="dialogChatWithdraw = true">
                    Close
                </v-btn>
            </div>

        </v-layout>
    </div>
</template>
<script>
    import {mapGetters} from 'vuex'
    import DialogWithComment from '../../common/elements/DialogWithComment'
    import DialogConfirm from '../../common/elements/DialogConfirm'

    export default {
        components:{
            'dialog-with-comments':DialogWithComment,
            'dialog-confirm': DialogConfirm,
        },
        data() {
            return {
                withdrawComment: '',
                dialogChatWithdraw: false,
                dialogContent:{
                    title: 'Close Issue',
                    text:`<p class="main-dialog__p">Are you sure you want to close this issue? This action cannot be undone, and you will not be able to write messages in the ticket. Though messages history will be available</p>`,
                    activateName: 'closeIssue',
                    submitBtn: 'CLOSE ISSUE',
                    cancelBtn: 'CANCEL',

                },
            }
        },
        props: [
            'guarantee_project'
        ],

        computed: {
            ...mapGetters(['user', 'issue']),
        },
        methods: {
            toSummary() {
                return this.$router.push({
                    name: 'summary',
                    params: {guarantee_project_id: this.$route.params.guarantee_project_id}
                })
            },
            toProjectSigned() {
                return this.$router.push({
                    name: 'project-signed',
                    params: {guarantee_project_id: this.$route.params.guarantee_project_id}
                })
            },
            closeIssue(value) {
                this.dialogChatWithdraw = false;

                api.post('/issue.close', {
                        id: this.$route.params.issue_id
                    })
                    .then(response => {
                        this.$router.push({
                            name: 'project-issues',
                            params: {guarantee_project_id: this.$route.params.guarantee_project_id}
                        })
                    })
            }
        }
    }
</script>
<style>

</style>
