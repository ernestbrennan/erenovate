<template>
    <div class="contracts-comp">

        <div :class="{ slide: slideSearchContracts }" class="contracts-comp__header row">

            <div class="cotracts-comp__search-box col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                <img :src="'/img/icon/search_gray.svg'" alt="search" class="cotracts-comp__search-icon">
                <input placeholder="Search Projects by title, description, PRO or HO name"
                       type="text" class="cotracts-comp__search"
                       data-v-step="cl-2"
                       v-model="query_search"
                       v-on:keyup.enter="getContracts"
                >
            </div>

            <div class="col-xl-4 col-lg-4 col-md-8 col-sm-8 col-8">
                <v-radio-group v-model="selectedState" class="cotracts-comp__search-radio">
                    <v-radio
                        v-for="state in searchStates"
                        :key="state.value"
                        :label="state.name"
                        :value="state.value"
                    ></v-radio>
                </v-radio-group>
            </div>

            <div>
                <v-btn fab depressed
                       class="cotracts-comp__submit-btn"
                       @click="getContracts">
                    <img :src="'/img/icon/arrow_gray.svg'" alt="">
                </v-btn>

                <template v-if="query_search.length > 0">
                    <v-tooltip bottom>
                        <img slot="activator" @click="query_search = ''; getContracts()" class="cotracts-comp__clean-search"
                             src="/img/icon/close__md_gray.svg" alt="clear_search">
                        <span>Clear and show all contracts</span>
                    </v-tooltip>
                </template>
            </div>

        </div>

        <div :style="{height: windowHeighth}" class="contracts-comp__body scrollbar">

            <template v-if="projects.length">
                <div @click="toProjectChatMobile(project.guarantee_project_id)"
                     class="contract-list"
                     :key="index"
                     v-for="(project, index) in projects"
                >
                    <div class="contract-list__img" data-v-step="cl-1">
                        <img :src="project.homeowner.photo" alt="">
                    </div>
                    <div class="contract-list__info">
                        <div class="contract-list__title">
                            {{project.title_pro}} <br>
                            {{project.title_ho}}
                        </div>
                        <div class="contract-list__text">
                            {{project.project_name}}
                            <span class="list-label hidden-md-and-up">{{project.issues_count}}</span>
                        </div>
                    </div>
                    <div class="contract-list__stage-box">
                        <div v-if="project.status == 'completed'" class="contract-list__text">
                            Completed <br>
                            Issues <span class="list-label">{{project.issues_count}}</span><br>
                            <span v-if="project.price_discrepancy" class="contract-list__price-alert">
                                Price variency issue</span>
                        </div>
                        <div v-else class="contract-list__text">
                            In progress <br>
                        </div>
                    </div>
                    <v-btn class="contract-list__link"
                           data-v-step="cl-3"
                           depressed
                           @click="project.status == 'completed'
                               ? toProjectChat(project.guarantee_project_id)
                               : toProjectSigned(project.guarantee_project_id)"
                    >
                        OPEN
                    </v-btn>
                </div>
            </template>

            <template v-else>
                <div class="contract-list__no-contracts"
                     :style="{'min-height': windowHeighth}"
                     v-if="!query_search"
                >
                    <div class="contract-list__no-contracts-inner">
                        <img class="common-img_center common-img_m-b-40-35" :src="'/img/contracts-list_empty.svg'"
                             alt="empty">
                        <h4 class="common-h4">No Projects with issues</h4>
                    </div>
                </div>

                <div class="contract-list__no-contracts"
                     :style="{'min-height': windowHeighth}"
                    v-else
                >
                    <div class="contract-list__no-contracts-inner">
                        <img class="common-img_center common-img_m-b-40-35" :src="'/img/search_null.svg'" alt="empty">
                        <h4 class="common-h4">No projects found</h4>
                        <p class="common-p">
                            Sorry, we didn’t find any matching projects.
                            Please check your spelling or try a different name.
                        </p>
                        <button class="common-btn common-btn_blue" @click="query_search = ''; getContracts()">
                            CLEAR SEARCH
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>

    import {mapGetters} from 'vuex'

    export default {
        data() {
            return {
                windowHeighth: false,
                query_search: '',
                searchStates:[
                    {name:'All', value:'all'},
                    {name:'Completed', value:'completed'},
                    {name:'In progress', value:'in_progress'}
                ],
                selectedState:'all'
            }
        },
        computed: {
            slideSearchContracts: {
                get() {
                    return this.$store.state.slideSearchContracts
                },
                set(val) {
                    this.$store.commit('set', {type: 'slideSearchContracts', data: val})
                }
            },
            ...mapGetters(["user", "projects"])
        },
        created() {
            this.containerHeigth();
            window.addEventListener('resize', this.containerHeigth);

            this.getContracts()
        },
        methods: {
            contractStatus(contract) {

                if (contract.status === 'pending') return 'Interviewing'

                else if (contract.status === 'signed') return 'Milestone ' + contract.current_milestone_sequence

                else if (contract.status === 'completed') return 'Completed'

                else return 'Visit project summary'
            },
            containerHeigth() {
                let width = window.innerWidth;
                let height = this.$store.state.containerHeight;
                let value = height - 90;
                if (width >= 992) {
                    this.windowHeighth = value + 'px'
                } else {
                    this.windowHeighth = height + 'px'
                }
            },

            toProjectSigned(guarantee_project_id) {
                return this.$router.push({
                    name: 'project-signed',
                    params: {guarantee_project_id: guarantee_project_id}
                })
            },
            toProjectChat(guarantee_project_id) {

                this.$router.push({
                    name: 'project-issues',
                    params: {guarantee_project_id: guarantee_project_id}
                })
            },
            toProjectChatMobile(guarantee_project_id) {
                if (window.innerWidth >= 768) {
                    return false
                }
                this.$router.push({
                    name: 'project-issues',
                    params: {guarantee_project_id: guarantee_project_id}
                })
            },
            getContracts() {
                this.$store.dispatch('getProjectList', {query_search: this.query_search, state_search: this.selectedState}).then(response => {

                });
            }
        },
    }
</script>
