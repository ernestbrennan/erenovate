<template>
    <div>
        <delete-draft-dialog v-model="dialog_delete_draft" :loading="loading" @delete="deleteArchivedDraft"/>

        <div class="contracts-comp">
            <div :class="{ slide: slideSearchContracts}"
                 class="contracts-comp__header project-list__contractor">
                <div class="cotracts-comp__search-box">
                    <img :src="'/img/icon/search_gray.svg'" alt="search" class="cotracts-comp__search-icon">
                    <input placeholder="Search a Project by title, description or Pro name"
                           type="text" class="cotracts-comp__search"
                           v-model="query_search"
                           v-on:keyup.enter="getArchivedDraftsList">
                    <template v-if="query_search.length > 0">
                        <v-tooltip bottom>
                            <img slot="activator" @click="query_search = ''; getArchivedDraftsList()"
                                 class="cotracts-comp__clean-search" src="/img/icon/close__md_gray.svg" alt="clear_search">
                            <span>Clear and show all projects</span>
                        </v-tooltip>
                    </template>
                </div>
                <v-btn fab depressed
                       class="cotracts-comp__submit-btn"
                       @click="getArchivedDraftsList">
                    <img :src="'/img/icon/arrow_gray.svg'" alt="">
                </v-btn>

                <button class="main-btn main-btn_full-blue" @click="toInvite">
                    START A PROJECT
                </button>

            </div>
            <div class="contracts-comp__body scrollbar" :style="{height: component_container_height}">
                <template v-if="archived_drafts.length">
                    <div class="contract-list" :key="index" v-for="(archived_draft, index) in archived_drafts"
                         @click="toArchivedDraft(archived_draft.id, true)">

                        <div class="contract-list__img">
                            <img :src="user.photo">
                        </div>
                        <div class="contract-list__info">
                            <div class="contract-list__title">
                                Project prepared by {{user.firstname}} {{user.lastname}}
                            </div>
                            <div class="contract-list__text">
                                <span class="main-text">{{ archived_draft.title}}</span>
                            </div>
                        </div>
                        <div class="contract-list__stage-box">
                            <div class="contract-list__title"></div>
                            <div class="contract-list__text"></div>
                        </div>

                        <button class="main-btn main-btn_border-red" @click="delete_id = archived_draft.id; dialog_delete_draft = true;">
                            DELETE
                        </button>
                        <v-btn class="contract-list__link" depressed @click="toArchivedDraft(archived_draft.id)">
                            OPEN
                        </v-btn>
                    </div>
                </template>

                <template v-else>

                    <div class="contract-list__no-contracts" :style="{'min-height': component_container_height}">
                        <div class="contract-list__no-contracts-inner">

                            <template v-if="!query_search">
                                <img class="common-img_center common-img_m-b-40-35" :src="'/img/contracts-list_empty.svg'"
                                     alt="empty">


                                <h4 class="common-h4">No Project Drafts found.</h4>

                                <p class="common-p">
                                    Let’s create Your 1<sup>st</sup> Project
                                </p>
                                <p class="common-p">
                                    The Start A Project button creates a new Project Scope, to share with
                                    clients. If you start a Project, you can always save it as a Draft, to
                                    finish later. Once your Project Scope is ready for a client to accept,
                                    attach the Signed Contract then click <i>Submit Project Scope to</i>
                                    automatically email the client a private link to the project page -- it’s
                                    like an eRenovate side-door. Then once your client accepts the
                                    Project Scope details, you’ll be notified. The Guarantee fee will be
                                    requested from you to activate the Guarantee.
                                    <strong>Close More Leads with Project Tracker</strong>.
                                </p>

                                <p class="common-p">Access Tutorial Videos from your Dashboard.</p>
                                <p class="common-p">Have questions about the eRenovate Guarantee?</p>

                                <p class="common-p">
                                    Feel free to
                                    <a class="common-link common-link_blue"
                                       href="https://www.erenovate.com/homeowners/contact" target="_blank">
                                        Contact us
                                    </a>

                                </p>

                            </template>

                            <template v-else>
                                <img class="common-img_center common-img_m-b-40-35" :src="'/img/search_null.svg'"
                                     alt="empty">
                                <h4 class="common-h4">No projects found</h4>
                                <p class="common-p">
                                    Sorry, we didn’t find any matching projects.
                                    Please check your spelling or try a different name.
                                    If you require assistance, please contact our
                                    <a class="common-link" href="https://www.erenovate.com/homeowners/contact">
                                        Contact Us.
                                    </a>
                                </p>
                                <button class="common-btn common-btn_blue"
                                        @click="query_search = ''; getArchivedDraftsList()">
                                    CLEAR SEARCH
                                </button>
                            </template>

                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>
</template>

<script>
    import ContractsTour from '../../../plugins/tour/contractList'
    import DeleteDialog from './dialogs/DeleteDialog';

    import {mapGetters} from 'vuex'

    export default {
        components: {
            'delete-draft-dialog' : DeleteDialog,
        },
        mixins: [ContractsTour],
        data() {
            return {
                query_search: '',
                dialog_delete_draft: false,
                delete_id: null,
                loading: false,
            }
        },
        computed: {
            ...mapGetters(["user", "archived_drafts"]),

            slideSearchContracts: {
                get() {
                    return this.$store.state.slideSearchContracts
                },
                set(val) {
                    this.$store.commit('set', {type: 'slideSearchContracts', data: val})
                }
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
            deleteArchivedDraft(){
                api.post('archived-draft.delete', {id: this.delete_id}).then(response => {

                    this.archived_drafts.splice(this.archived_drafts.findIndex((item, index) => item.id === this.delete_id), 1);

                    this.dialog_delete_draft = false;
                    this.delete_id = null;
                    this.loading = false;

                    this.$store.commit('set', {
                        type: 'errorAlert',
                        data: {
                            type: 'info',
                            text: 'Project Scope Draft has been deleted'
                        }
                    });
                })
            },
            toInvite() {
                this.$router.push({
                    name: 'invite-ho',
                })
            },
            toArchivedDraft(archived_draft_id, mobile = false) {
                if (mobile && window.innerWidth >= 768) {
                    return false
                }

                this.$router.push({
                    name: 'invite-ho',
                    params: {archived_draft_id: archived_draft_id}
                })
            },
            getArchivedDraftsList() {
                this.$store.state.global_loader = true;
                this.$store.dispatch('getArchivedDraftsList', this.query_search).then(response => {
                    this.$store.state.global_loader = false;
                });
            }
        },
        created() {
            if (this.user.role === 'homeowner') {
                return this.$router.push({name: 'projects-list'})
            }

            this.getArchivedDraftsList()
        },
    }
</script>
