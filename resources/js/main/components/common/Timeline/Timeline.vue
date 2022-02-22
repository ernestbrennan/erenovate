<template>
    <div class="contract-timeline-comp scrollbar component-scroll"
         :style="{height: componetConteinerHeight}"
    >
        <div class="contract-timeline">
            <ul class="contract-timeline__list" data-v-step="ilho-2" v-if="timeline">

                <timeline-elem data-v-step="mls-1" :elem="timeline.contract" :to="toTimeLineLink(timeline.contract)"/>


                <timeline-elem data-v-step="ci-9" v-if="$route.name === 'create-invoice' || $route.name === 'list-invoice'"
                               :elem="{status: '', title: getTitle()}"
                               :to="{name: $route.name, params: $route.params}" />

                <div data-v-step="ci-10" class="sum-tour-4a"></div>

                <timeline-elem v-if="$route.name === 'current-invoice' || $route.name === 'history-invoice'"
                               :elem="timeline.invoice || {status: 'in_progress', title: getTitle()}"
                               :extrClass="currentInvoiceClass"
                               :to="{name: $route.name, params: $route.params}"/>

                <timeline-elem data-v-step="mls-4" v-for="(item, index) in timeline.milestones"
                               :key="index"
                               :elem="item"
                               :to="toTimeLineLink(item)"/>

                <timeline-elem :elem="timeline.completed" :to="toTimeLineLink(timeline.completed)"/>

            </ul>
        </div>
        <div style="display: block; width:100%;height: 15px; padding:15px;" class="sum-tour-4"></div>
        <div class=" contract-timeline-comp__btn-box">
            <button class=" main-btn main-btn_border-blue" @click="toMessages">
                 MESSENGER
                 <template v-if="chat.unread_messages_count !== 0 && typeof chat.unread_messages_count !== 'undefined'"><!-- if counter !== 0--> <span class="msg-counter">
                         {{chat.unread_messages_count}}
                     </span>
                 </template>
            </button>
            <a href="mailto:tom@erenovate.com">
                <button class="main-btn main-btn_border-blue">
                    Contact Us
                </button>
            </a>
        </div>
    </div>
</template>

<script>
    import TimelineElem from './TimelineElem';
    import {mapGetters} from 'vuex'

    export default {
        components: {
            'timeline-elem': TimelineElem
        },
        data() {
            return {
                currentInvoiceClass: 'ciho-6 ciho-7',
                types: {
                    signed: 'signed',
                    completed: 'completed',
                    in_progress: 'in_progress',
                    pending: 'pending',
                },

                invoice: {
                    title: 'Payment Request',
                    created_at: '',
                    type: 'invoice'
                }
            }
        },
        computed: {
            ...mapGetters([ "timeline","user", "chat"]),
            componetConteinerHeight(){
                let height = this.$store.state.containerHeight;
                return height + 'px';
            },
        },
        methods: {
            toMessages() {
                this.$router.push({
                    name: 'messages',
                    params: {
                        guarantee_project_id: this.$route.params.guarantee_project_id
                    }
                })
            },
            getTitle(){
                if (this.$route.name === 'current-invoice' || this.$route.name === 'create-invoice') {
                    return 'Payment Request';
                }

                return 'Payment Requests'
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
        },
        created(){
            this.$store.dispatch('getChat', this.$route.params.guarantee_project_id).then(response => {
                let params = {
                    chat_id: this.chat.id,
                    offset: this.chat.messages.length
                };
            })
        },
        beforeDestroy(){
            this.$store.state.timeline = null;
        }
    }
</script>
