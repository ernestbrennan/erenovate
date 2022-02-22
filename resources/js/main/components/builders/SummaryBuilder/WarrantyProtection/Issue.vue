<template>
    <div v-if="issue.type !== 'price_modification'"   @click="toIssueMob" class="ticket sum-tour-12s">
        <div class="ticket__info-box">
            <div class="ticket__id">Issue #{{issue.sequence}}</div>
            <div class="ticket__desc">{{issue.title}}</div>
        </div>
        <div class="ticket__type-box">
            <div class="ticket__label">Stage</div>
            <div class="ticket__type">{{status}}</div>
        </div>
        <div class="ticket__button-box">
            <button @click="toIssue" class="main-btn main-btn_border-blue-deep">
                VIEW
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        props:[
            'issue'
        ],
        data(){
            return {
                statuses:{
                    opened: 'opened',
                    closed: 'closed',
                    under_review: 'under_review'
                },
            }
        },
        computed: {
            status(){
                if (this.issue.status === this.statuses.opened) return 'Opened';
                if (this.issue.status === this.statuses.closed) return 'Resolved';
                if (this.issue.status === this.statuses.under_review) return 'Under review';
            }
        },
        methods:{
            getType(type){
                if (type === this.type.open){return 'Open'}
                if (type === this.type.close){return 'Resolved'}
                if (type === this.type.under_review){return 'Under review'}
            },
            toIssue(){
                this.$router.push({
                    name: 'issue',
                    params: {
                        guarantee_project_id: this.$route.params.guarantee_project_id,
                        issue_id: this.issue.id,
                    }
                })
            },
            toIssueMob(){
                if(window.innerWidth >=576){ return false}
                this.toIssue();
            },
        },
    }
</script>

<style scoped>

</style>