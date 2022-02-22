<template>
    <div class="contracts-comp">
        <div :class="{ slide: slideSearchContracts , 'project-list__contractor': invite_ho}"
             class="contracts-comp__header">
            <div class="cotracts-comp__search-box">
                <img :src="'/img/icon/search_gray.svg'" alt="search" class="cotracts-comp__search-icon">
                <input placeholder="Search a Project by title, description or Pro name"
                       type="text" class="cotracts-comp__search"
                       data-v-step="cl-2"
                       v-model="query_search"
                       v-on:keyup.enter="getContracts">
                <template v-if="query_search.length > 0">
                    <v-tooltip bottom>
                        <img slot="activator" @click="query_search = ''; getContracts()"
                             class="cotracts-comp__clean-search" src="/img/icon/close__md_gray.svg" alt="clear_search">
                        <span>Clear and show all projects</span>
                    </v-tooltip>
                </template>
            </div>
            <v-btn fab depressed
                   class="cotracts-comp__submit-btn"
                   @click="getContracts">
                <img :src="'/img/icon/arrow_gray.svg'" alt="">
            </v-btn>

            <button class="main-btn main-btn_full-blue"
                    v-if="invite_ho"
                    @click="toInvite">
                START A PROJECT
            </button>

        </div>
        <div class="contracts-comp__body scrollbar" :style="{height: component_container_height}">
            <template v-if="this.contracts.length">
                <div @click="toProjectChat(contract.guarantee_project_id, true)"
                     class="contract-list"
                     :key="index"
                     v-for="(contract, index) in contracts">

                    <div class="contract-list__img" data-v-step="cl-1">
                        <img :src="contract.target_user.photo" alt="">
                    </div>
                    <div class="contract-list__info">
                        <div class="contract-list__title">
                            {{ contract.title}}
                        </div>
                        <div class="contract-list__text">
                            <span class="main-text">{{ contract.project_name}}</span>
                            <message-count :contract="contract"/>
                        </div>
                    </div>
                    <div class="contract-list__stage-box">
                        <div class="contract-list__title">Stage</div>
                        <div class="contract-list__text">
                            {{contractStatus(contract.contract)}}
                        </div>
                    </div>
                    <v-btn class="contract-list__link"
                           data-v-step="cl-3"
                           depressed
                           @click="toProjectChat(contract.guarantee_project_id)">
                        OPEN

                        <message-count :contract="contract"/>

                    </v-btn>
                </div>
            </template>

            <template v-else>

                <div class="contract-list__no-contracts" :style="{'min-height': component_container_height}">
                    <div class="contract-list__no-contracts-inner">

                        <template v-if="!query_search">
                            <img class="common-img_center common-img_m-b-40-35"
                                 :src="'/img/contracts-list_empty.svg'"
                                 alt="empty">


                            <template v-if="user.role === 'contractor'">
                                <h4 class="common-h4" data-v-step="cl-1">Welcome to Project Tracker!</h4>

                                <p class="common-p">
                                    Let’s create Your 1<sup>st</sup>    Project
                                </p>
                                <p class="common-p">
                                    Project Tracker is your easy tool tool to stay in sync with clients, give them
                                    clarity and offer the eRenovate Guarantee. Get started by clicking the button
                                    below, to create a Project Scope to outline the project details and milestones.
                                    We will email your client a direct link to your project. It’s like a back-door to
                                    eRenovate. Once the clients accepts the Project Scope (draft), you’ll be
                                    notified, and receive a PayPal Invoice. Payment is required to activate the
                                    Guarantee for this project.
                                </p>

                                <p class="common-p">
                                    Watch Project Scope Tutorial Videos
                                    <a class="common-link common-link_blue" href="https://erenovate.com/contractors/Tutorials" target="_blank" >
                                        here
                                    </a>
                                    .
                                </p>
                                <p class="common-p">
                                    Have questions about the eRenovate Guarantee?
                                </p>
                                <p class="common-p">
                                    Feel free to
                                    <a class="common-link common-link_blue"
                                       href="https://www.erenovate.com/homeowners/contact"
                                       target="_blank" >
                                     Contact Us
                                    </a>
                                </p>
                                <button class="common-btn common-btn_blue" @click="toInvite">
                                    START NEW PROJECT
                                </button>
                            </template>

                            <template v-else>
                                <h4 class="common-h4" data-v-step="cl-1">Start A Project</h4>

                                <p class="common-p">

                                    To create your first Project on eRenovate, simply click the Post A Project button
                                    below.
                                    We will match your project to 3 Bonded Pros.
                                    Once you decide which Pro to hire, click the <b>Hire with Guarantee</b> button
                                    to ensure your project is
                                    covered by the eRenovate Guarantee!

                                </p>
                                <p class="common-p">
                                    Have questions about the eRenovate Guarantee? <br>
                                    Feel free to
                                    <a class="common-link common-link_blue"
                                       href="https://www.erenovate.com/homeowners/contact"
                                       target="_blank" >
                                    Contact Us
                                    </a>
                                </p>
                                <a target="_blank" href="http://dev.erenovate.com/homeowners/postjob">
                                    <button data-v-step="cl-3" class="common-btn common-btn_blue">
                                        POST A PROJECT
                                    </button>
                                </a>
                            </template>
                        </template>

                        <template v-else>
                            <img class="common-img_center common-img_m-b-40-35" :src="'/img/search_null.svg'"
                                 alt="empty">
                            <h4 class="common-h4" data-v-step="cl-1">No projects found</h4>
                            <p class="common-p">
                                Sorry, we didn’t find any matching projects.
                                Please check your spelling or try a different name.
                                If you require assistance, please contact our
                                <a class="common-link" href="https://www.erenovate.com/homeowners/contact">Contact
                                    Us.</a>
                            </p>
                            <button data-v-step="cl-3" class="common-btn common-btn_blue"
                                    @click="query_search = ''; getContracts()">
                                CLEAR SEARCH
                            </button>
                        </template>

                    </div>
                </div>
            </template>

            <template v-if="getTour">
                <v-tour name="tourContracts" :steps="steps" :options="optionsVueTour"
                        :callbacks="contractsCallbacks"></v-tour>
            </template>
        </div>
    </div>
</template>

<script>
    import MessageCount from './MessageCount'
    import ContractsTour from '../../../plugins/tour/contractList'
    import tourInit from '../../../plugins/tour/init'

    import {mapGetters} from 'vuex'

    export default {
        mixins: [tourInit, ContractsTour],
        components: {
            'message-count': MessageCount
        },
        data() {
            return {
                query_search: '',
            }
        },
        computed: {
            ...mapGetters(["user", "contracts"]),

            slideSearchContracts: {
                get() {
                    return this.$store.state.slideSearchContracts
                },
                set(val) {
                    this.$store.commit('set', {type: 'slideSearchContracts', data: val})
                }
            },

            invite_ho() {
                return this.user.role === 'contractor' && this.contracts.length;
            },
            component_container_height() {
                let width = window.innerWidth;
                let height = this.$store.state.containerHeight;
                let value = height - 90;
                if (width >= 992) {
                    return value + 'px'
                } else {
                    return height + 'px'
                }
            },
        },
        methods: {
            contractStatus(contract) {
                if (contract.status === 'pending') return 'Project Scope Draft';
                else if (contract.has_invoice) return 'Payment request';
                else if (contract.has_issue) return 'Unresolved Issue';
                else if (contract.status === 'signed') return 'Milestone ' + contract.current_milestone_sequence;
                else if (contract.status === 'completed') return 'Completed';
                else return 'Visit project summary';
            },

            toProjectChat(guarantee_project_id, mobile = false) {
                if (mobile && window.innerWidth >= 768) {
                    return false
                }

                this.$router.push({
                    name: 'messages',
                    params: {guarantee_project_id: guarantee_project_id}
                })
            },
            toInvite() {
                this.$router.push({
                    name: 'invite-ho',
                })
            },
            getContracts() {
                this.$store.state.global_loader = true;
                this.$store.dispatch('getContractsList', this.query_search).then(response => {
                    this.$store.state.global_loader = false;
                });
            }
        },
        created() {
            this.$store.state.global_loader = true;
            this.$store.dispatch('getContractsList').then(response => {
                this.$store.state.global_loader = false;
            })
        },
    }
</script>
