<template>
    <div class="summary-block">
        <h3 class="summary-block__border-title">88 Day Workmanship Guarantee</h3>
        <div class="summary-block__gray-box">
            <template v-if="issues.length">
                <div class="tickets-list sum-tour-ho2s">
                    <issue :key="issue.id" v-for="issue in issues" :issue="issue"></issue>
                    <button  @click="dialogIssue = true" v-if="user.role === 'homeowner'" class="sum-tour-13s main-btn main-btn_full-blue open-new-issue">
                        Report an issue
                    </button>
                </div>
            </template>
            <template v-else>
                <empty-issue @newIssue="dialogIssue = true"></empty-issue>
            </template>
            <issue-dialog v-model="dialogIssue"></issue-dialog>
        </div>
    </div>
</template>

<script>
    import issueDialog from './IssueDialog'
    import {mapGetters} from 'vuex'
    import emptyIssue from './EmptyIssue'
    import Issue from './Issue'

    export default {
        components: {
            'empty-issue': emptyIssue,
            'issue': Issue,
            'issue-dialog': issueDialog,
        },
        data() {
            return {
                dialogIssue: false,
            }
        },
        props: [
            'issues'
        ],
        computed: {
            ...mapGetters(["user"])
        },
    }
</script>

