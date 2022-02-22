<template>
    <div class="contracts-comp">
        <div class="contracts-comp__header contracts-comp__header_issue-list">
            <v-spacer></v-spacer>
            <v-menu left offset-y nudge-top="-16">
                <v-btn class="header__user-dropdown main-btn_border-blue-deep main-btn"
                        slot="activator"
                        color="primary"
                        flat
                >
                    ISSUE TYPE
                </v-btn>
                <div class="contract-header__drop-target">
                    <ul class="option-list-dropdown">
                        <li @click="filterIssues('price_modification')" class="option-list-dropdown__el">
                            <span>Price modification</span>
                        </li>
                        <li @click="filterIssues('standard')" class="option-list-dropdown__el">
                            <span>Standard issue</span>
                        </li>
                        <li @click="filterIssues('all')" class="option-list-dropdown__el">
                            <span>All</span>
                        </li>
                    </ul>
                </div>
            </v-menu>

            <v-btn class="main-btn_border-blue-deep main-btn"
                   color="primary"
                   flat
                   @click="toProjectSigned()"
            >
                PROJECT SCOPE
            </v-btn>
        </div>
        <div :style="{height: windowHeighth}"
             class="contracts-comp__body scrollbar"
        >
            <div class="contract-list"
                 :key="index"
                 v-for="(issue, index) in filteredIssue"
                 @click="toMobile(issue.id)"
            >
                <div class="contract-list__info">
                    <template v-if="issue.type === 'price_modification'">
                        <div class="contract-list__title">
                            <span class="list-label list-label_red">
                                Price Modification
                            </span> Issue #{{issue.id}}
                        </div>
                        <div class="contract-list__text">
                            Price Discrepancy
                        </div>
                    </template>
                    <template v-else>
                        <div class="contract-list__title">
                           Workmanship Issue #{{issue.id}}: {{ issue.title}}
                        </div>
                        <div class="contract-list__text">

                            {{ issue.description}}
                        </div>
                    </template>
                </div>
                <div class="contract-list__stage-box">
                    <div class="contract-list__text">{{status(issue.status)}}</div>
                </div>
                <v-btn class="contract-list__link"
                       data-v-step="cl-3"
                       depressed
                       @click="to(issue.id)">
                    OPEN
                </v-btn>
            </div>

        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        data() {
            return {
                filter_type: 'all',
                filteredIssue: [],
                windowHeighth: false,
                querySearch: '',
                emptyContracts: true,
                enableSearch: false,

                guarantee_project_id: this.$route.params.guarantee_project_id
            }
        },
        computed: {
            ...mapGetters(["issues"]),

        },
        created() {
            this.containerHeigth();
            window.addEventListener('resize', this.containerHeigth);

            this.$store.dispatch('getIssues', this.guarantee_project_id).then(response => {
                console.log(this.issues);
                this.filteredIssue = this.issues;
            })
        },
        methods: {
            filterIssues(filter_type){
                if(filter_type === 'all'){
                    this.filteredIssue = this.issues
                } else if (filter_type === 'standard'){
                    this.filteredIssue = this.issues.filter((el)=>{
                        return el.type !== 'price_modification';
                    })
                } else if (filter_type === 'price_modification'){
                    console.log('hello price');
                    this.filteredIssue = this.issues.filter((el)=>{
                        return el.type === 'price_modification';
                    })
                }
            },
            changeType(type) {
                console.log(type);
            },
            status(status) {
                if (status === 'opened') return 'Opened';
                if (status === 'closed') return 'Resolved';
                if (status === 'under_review') return 'Under Review';
            },
            containerHeigth() {
                let width = window.innerWidth;
                let height = this.$store.state.containerHeight;
                let value = height - 97;
                if (width >= 992) {
                    this.windowHeighth = value + 'px'
                } else {
                    this.windowHeighth = height + 'px'
                }
            },

            toProjectSigned() {
                return this.$router.push({
                    name: 'project-signed',
                    params: {guarantee_project_id: this.$route.params.guarantee_project_id}
                })
            },
            to(issue_id) {
                this.$router.push({
                    name: 'issue',
                    params: {
                        guarantee_project_id: this.guarantee_project_id,
                        issue_id: issue_id,
                    }
                })
            },
            toMobile(issue_id) {
                if (window.innerWidth >= 768) {
                    return false
                }
                this.to(issue_id);
            },
        },
    }
</script>
