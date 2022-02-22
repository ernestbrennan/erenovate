<template>
    <div>
        <v-menu class="header-slot__timeline"
                top
                offset-x
                offset-y
                left
                content-class="scrollbar hidden-lg-and-up"
                :nudge-left="nudgeTimeline"
                :min-width="widthTimeline"
                max-width="355"
                :max-height="heightTimeline"
        >
            <div class="header-slot__title" slot="activator">
                <img class="header-slot__title-img" src="/img/icon/contract_gray.svg" alt="contract">
                <span class="header-slot__title-text">Project "{{ project_name | substring_35 }}"</span>
                <img src="/img/icon/caret-icon_gray.svg" alt="" style="transform: rotate(180deg)">
            </div>

            <div class="contract-timeline">
                <ul class="contract-timeline__list" v-if="timeline">
                    <h4 class="mobile-title">Project Stages</h4>
                    <h5 class="mobile-subtitle">Select Stage to Navigate</h5>


                    <timeline-elem :elem="timeline.contract" :to="toTimeLineLink(timeline.contract)"/>


                    <timeline-elem v-if="$route.name === 'create-invoice' || $route.name === 'list-invoice'"
                                   :elem="{status: 'in_progress', title: getTitle()}"
                                   :to="{name: $route.name, params: $route.params}"/>

                    <timeline-elem v-if="$route.name === 'current-invoice' || $route.name === 'history-invoice'"
                                   :elem="timeline.invoice || {status: 'in_progress', title: getTitle()}"
                                   :to="{name: $route.name, params: $route.params}"/>

                    <timeline-elem v-for="(item, index) in timeline.milestones"
                                   :key="index"
                                   :elem="item"
                                   :to="toTimeLineLink(item)"/>

                    <timeline-elem :elem="timeline.completed" :to="toTimeLineLink(timeline.completed)"/>
                </ul>
            </div>
        </v-menu>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import TimelineElem from './TimelineElem'

    export default {
        components:{
            'timeline-elem': TimelineElem
        },
        data() {
            return {
                widthTimeline: false,
                nudgeTimeline: false,
                types: {
                    signed: 'signed',
                    completed: 'completed',
                    in_progress: 'in_progress',
                    pending: 'pending',

                },
            }
        },
        computed: {
            ...mapGetters(['timeline',  'guarantee_project']),
            heightTimeline() {
                return window.innerHeight - 50
            },
            project_name(){
                return this.guarantee_project.project_name || '#' + this.guarantee_project_id
            },
        },
        methods: {
            getTitle(){
                if (this.$route.name === 'current-invoice' || this.$route.name === 'create-invoice') {
                    return 'Invoice';
                }

                return 'Invoices'
            },
            toTimeLineLink(item) {
                if (item.type === 'contract') {
                    return {
                        name: 'contract-signed',
                        params: {
                            guarantee_project_id: this.$route.params.guarantee_project_id
                        }
                    }

                } else if (item.type === 'milestone') {
                    if (item.status === 'in_progress') {

                        return {
                            name: 'current-milestone',
                            params: {
                                guarantee_project_id: this.$route.params.guarantee_project_id
                            }
                        };

                    } else if (item.status === 'completed' || item.status === 'pending') {

                        return {
                            name: 'by-id-milestone',
                            params: {
                                guarantee_project_id: this.$route.params.guarantee_project_id,
                                milestone_id: item.id
                            }
                        };
                    }

                } else if (item.type === 'invoice') {

                    return {
                        name: 'current-invoice',
                        params: {
                            guarantee_project_id: this.$route.params.guarantee_project_id
                        }
                    }

                } else if (item.type === 'finish') {
                    return {
                        name: 'summary',
                        params: {
                            guarantee_project_id: this.$route.params.guarantee_project_id
                        }
                    };
                }

            },
            widthTimelineFun() {
                if (window.innerWidth >= 768) {
                    this.nudgeTimeline = '-265'
                    this.widthTimeline = 355
                } else {
                    this.nudgeTimeline = '-185'
                    this.widthTimeline = 245
                }
            },
            statusText(status) {
                if (status === 'signed') return 'Signed';
                if (status === 'completed') return 'Completed';
                if (status === 'in_progress') return 'In Progress';
                if (status === 'pending') return 'Pending';
            },
            statusClass(status) {
                if (status === 'signed') return this.types.signed;
                if (status === 'completed') return this.types.completed;
                if (status === 'in_progress') return this.types.in_progress;
                if (status === 'pending') return this.types.pending;
            },
        },
        created() {
            this.widthTimelineFun()
            window.addEventListener('resize', this.widthTimelineFun)
        }
    }
</script>

<style scoped>

</style>
