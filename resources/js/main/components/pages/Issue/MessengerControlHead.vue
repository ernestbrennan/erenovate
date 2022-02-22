<template>
    <div class="chat__header hidden-sm-and-down" data-v-step="ms-1">
        <dialog-confirm v-model="dialogChatWithdraw"
                        :content="dialogContent"
                        reverse="true"
                        :loading="loading"
                        @afterSubmit="loading = false"
                        @submit="closeIssue" />

        <v-layout row wrap align-center class="messenger-ho-9b">
            <div class="chat-control__row">
                <v-btn flat class="issue-tour-1 chat-control__btn chat-control__file-history" @click="toSummary">
                    BACK TO SUMMARY
                </v-btn>
            </div>
            <p class="common-p issues-messenger-title">{{get_title}}</p>
            <v-spacer></v-spacer>

            <div class="chat-control__row" v-if="user.role === 'homeowner' && issue.status !== 'closed'">
                <v-btn flat class="issue-ho-3a chat-control__btn chat-control__withdraw" @click="closeIssueDialog">
                    CLOSE ISSUE
                </v-btn>
            </div>

        </v-layout>
    </div>
</template>
<script>
    import {mapGetters} from 'vuex'
    import DialogConfirm from '../../common/elements/DialogConfirm'

    export default {
        components: {
            'dialog-confirm': DialogConfirm,
        },
        data() {
            return {
                loading: false,
                withdrawComment: '',
                dialogContent: {
                    title: '',
                    text: ``,
                    activateName: '',
                    submitBtn: '',
                    cancelBtn: '',
                },
                dialogContentPrice: {
                    title: 'Close Price Discrepancy Issue',
                    text: `<p class="main-dialog__p">
                        Thank you for reporting a final Project Price Discrepancy.
                        This issue has been resolved, is now closed, and will be shown in the Messages screen.
                    </p>`,
                    activateName: 'closeIssue',
                    submitBtn: 'CLOSE ISSUE',
                    cancelBtn: 'CANCEL',
                },
                dialogContentNormal: {
                    title: 'Close Issue',
                    text: `<p class="main-dialog__p">
                            Great, and thank you for resolving this issue. The issue will now be closed and the Issue
                            messages will be archived.</p>`,
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
            ...mapGetters(['user', 'issue', 'summary']),
            dialogChatWithdraw: {
                get() {
                    if (this.issue.type === 'price_modification') {
                        this.dialogContent = this.dialogContentPrice;
                    } else {
                        this.dialogContent = this.dialogContentNormal;
                    }
                    return this.$store.state.issueDialogClose;
                },
                set(value) {
                    if (this.issue.type === 'price_modification') {
                        this.dialogContent = this.dialogContentPrice;
                    } else {
                        this.dialogContent = this.dialogContentNormal;
                    }
                    return this.$store.commit('set', {type: 'issueDialogClose', data: value});
                },
            },
            get_title() {
                if (this.issue.type === 'price_modification') {
                    return 'Price discrepancy issue #' + this.issue.sequence;
                } else {
                    return 'Workmanship Issue #' + this.issue.sequence;
                }
            },
        },
        methods: {
            closeIssueDialog() {
                this.dialogChatWithdraw = true
            },
            toSummary() {
                return this.$router.push({
                    name: 'summary',
                    params: {guarantee_project_id: this.$route.params.guarantee_project_id}
                });
            },
            resizeTextarea(event) {
                event.target.style.height = 50 + 'px'
                event.target.style.height = (event.target.scrollHeight) + 'px'
            },
            closeIssue() {
                api
                    .post('issue.close', {
                        id: this.$route.params.issue_id
                    })
                    .then(response => {
                        this.loading = false;
                        this.toSummary();
                    })
            }
        }
    }
</script>
<style>

</style>
