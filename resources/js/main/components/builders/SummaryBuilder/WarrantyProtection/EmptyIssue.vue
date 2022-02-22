<template>
    <div class="claim-empty">
        <img src="/img/protection-icon.svg" alt="" class="claim-empty__img sum-tour-ho2s">
        <div class="claim-empty__title sum-tour-12s">{{title}}</div>
        <div class="claim-empty__text">{{text}}</div>
        <button @click="openNewIssue" v-if="user.role === 'homeowner'" class="sum-tour-13s claim-empty__btn main-btn main-btn_full-blue">
            REPORT AN ISSUE
        </button>
    </div>
</template>

<script>

    import {mapGetters} from 'vuex'

    export default {
        computed: {
            text() {
                if (this.user.role === 'homeowner') {
                    return 'Ok, your 88 Day Workmanship Guarantee\n' +
                        'has been activated. In the rare instance you\n' +
                        'need to report a Workmanship Issue, simply\n' +
                        'click the Report an Issue button below to\n' +
                        'notify eRenovate, then we will automatically\n' +
                        'notify the Pro. The vast majority of Issues\n' +
                        'are resolved directly between homeowners\n' +
                        'and the Pros. Have Peace of Mind knowing\n' +
                        'an eRenovate Project Success Advisor is\n' +
                        'available if we need to lend a hand.'
                }
                return 'This is eRenovate guarantee section. ' +
                    'Here we will display issues Homeowner been reported about. ' +
                    'Please use our message interface to communicate with client ' +
                    'and discuss issue details and propose options to solve found issue. ' +
                    'If you use our communication interface we will be able to track issue ' +
                    'discussion details and protect you in case you can’t find common point with a client.'
            },
            title() {
                if (this.user.role === 'homeowner') {
                    return 'No issues reported'
                }
                return 'We have no Issues submitted'
            },
            ...mapGetters(["user"])
        },
        methods:{
            openNewIssue(){
                this.$emit('newIssue')
            },
        },
    }
</script>

<style scoped>

</style>