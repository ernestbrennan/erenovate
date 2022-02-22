<template>
    <div>
        <complete-dialog v-model="complete_milestone_modal"/>

        <edit-dialog v-model="edit_milestone_modal" :loading="dialog_loading" @edit="editMilestone"/>

        <dialog-with-comment v-model="isDialogWithComOpen"
                             :content="dialog_content"
                             :dialog_loading="dialog_loading"
                             @submit="submitDialog"
                             @loading="dialog_loading = $event.value"
        />

        <div ref="contractHeader" class="contract-comp__header">
            <div class="g-flex g-flex_row g-flex_wrap margin-10 g-flex_vertical-center">

                <div class="contract-header__title hidden-sm-and-down">
                    Milestone {{ current_milestone.sequence}}
                    <span>{{ milestone.milestone_content.start_date}}</span>
                </div>

                <!--When milestone completed-->
                <template v-if="milestone.status === 'completed'">
                    <div class="contract-header__contract-label hidden-md-and-down">
                        Completed
                    </div>
                    <v-spacer class="hidden-md-and-down"></v-spacer>

                    <button class="main-btn main-btn_border-blue-deep" @click="toInvoice">
                        VIEW REQUEST
                    </button>
                </template>

                <!--When propose invoice -->
                <template v-else-if="has_invoice">
                    <div class="contract-header__contract-label hidden-md-and-down" v-if="user.role === 'homeowner'">
                        Waiting for payment verification
                    </div>
                    <v-spacer class="hidden-md-and-down"></v-spacer>

                    <button data-v-step="mls-2" class="main-btn main-btn_border-blue-deep" @click="toInvoice">
                        VIEW PAYMENT REQUEST
                    </button>
                </template>

                <!--When rejected propose -->
                <template v-else-if="milestone.milestone_content.status === 'rejected'">
                    <div class="contract-header__contract-label hidden-md-and-down">
                        Declined
                    </div>
                    <v-spacer class="hidden-md-and-down"></v-spacer>
                </template>

                <!--When edit milestone and load edited milestone -->
                <template v-else-if="milestone.milestone_content.status === 'pending'">
                    <div class="contract-header__contract-label" :class="{'hidden-md-and-down': !is_edited_by_current_user }">
                        Edited Milestone
                    </div>

                    <v-spacer class="hidden-md-and-down"></v-spacer>

                    <template v-if="!is_edited_by_current_user">
                        <button class="main-btn main-btn_border-red" @click="showDialog('decline')">
                            DECLINE
                        </button>
                        <v-spacer class="hidden-md-and-up"></v-spacer>
                        <button class="main-btn main-btn_full-green" @click="showDialog('accept')">
                            ACCEPT
                        </button>
                    </template>

                    <button class="main-btn main-btn_border-blue-deep hidden-md-and-down" @click="back">
                        BACK
                    </button>
                </template>

                <!--When edit milestone and not load edited milestone -->
                <template v-else-if="milestone.edited.is_edited">
                    <div class="contract-header__contract-label" :class="{'hidden-md-and-down': !is_edited}"  v-if="is_edited_by_current_user">
                        Waiting for {{ user.role === 'homeowner' ? 'PRO' : 'HO'}} to accept milestone edit
                    </div>
                    <div class="contract-header__contract-label hidden-md-and-down" v-else>
                        Milestone edit has been proposed
                    </div>

                    <v-spacer class="hidden-md-and-down"></v-spacer>

                    <button data-v-step="mls-2" class="main-btn main-btn_border-blue-deep"
                            @click="$emit('getEditedMilestone')">
                        VIEW MILESTONE EDIT
                    </button>
                </template>

                <!--When milestone not edited -->
                <template v-else-if="this.milestone.milestone_content.status === 'active'">

                    <template v-if="read_only_milestone">
                        <v-spacer class="hidden-md-and-down"></v-spacer>
                        <button class="mls-5 main-btn_border-green main-btn"
                                v-if="can_complete"
                                @click="complete_milestone_modal = true">
                            COMPLETE
                        </button>
                        <v-spacer class="hidden-md-and-up"></v-spacer>
                        <button class="main-btn main-btn_border-blue-deep"
                                @click="$store.state.read_only_milestone = false">
                            {{propose_text}}
                        </button>
                    </template>

                    <template v-else>
                        <div class="contract-header__contract-label hidden-md-and-down">
                            Editing Milestone
                        </div>
                        <v-spacer class="hidden-md-and-down"></v-spacer>

                        <button class="main-btn main-btn_border-red" @click="backToPrevious">
                            CANCEL
                        </button>
                        <v-spacer class="hidden-md-and-up"></v-spacer>
                        <button class="main-btn main-btn_full-green" @click="validate('edit')">
                            {{propose_text}}
                        </button>
                    </template>

                </template>

            </div>
        </div>

        <template v-if="getTour">
            <v-tour name="tourMilestone" :steps="steps" :options="optionsVueTour" :callbacks="tourCallbacks"></v-tour>
        </template>

    </div>
</template>
<script>
    import {mapGetters} from 'vuex'

    import {proposalConfDialog, acceptEditDialog, rejectEditDialog } from '../../dialog/milestoneEditing';

    import InitTour from '../../../plugins/tour/init'
    import MilestoneTour from '../../../plugins/tour/milestone'
    import validate_milestone from '../../mixins/validator/milestone'
    import DialogWithComment from '../../common/elements/DialogWithComment'
    import {ChildMixin} from '../../common/Mixins/glValidate/mixins.js'

    import CompleteDialog from './dialos/CompleteDialog'
    import EditDialog from './dialos/EditDialog'

    export default {
        components:{
            'complete-dialog': CompleteDialog,
            'edit-dialog': EditDialog,
            'dialog-with-comment': DialogWithComment
        },
        mixins: [InitTour, MilestoneTour, validate_milestone, ChildMixin],
        data() {
            return {
                complete_milestone_modal: false,
                edit_milestone_modal: false,
                edit_milestone_dialog: proposalConfDialog,
                accept_milestone_dialog: acceptEditDialog,
                decline_milestone_dialog: rejectEditDialog,
                dialog_comment: '',
                isDialogWithComOpen: false,
                dialog_content: proposalConfDialog,
                dialog_loading:false,
            }
        },
        props: {
            milestone: Object
        },
        computed: {
            ...mapGetters(["user", "read_only_milestone", "guarantee_project", "current_milestone"]),

            propose_text() {
                return window.innerWidth > 992 ? 'PROPOSE MILESTONE EDIT' : this.read_only_milestone ?  'EDIT' : 'SUBMIT EDIT'
            },
            has_invoice() {
                return this.milestone.invoice || false;
            },
            is_edited(){
                return this.current_milestone.status === 'in_progress' && this.current_milestone.milestone_content.status === 'pending'
            },
            is_edited_by_current_user() {
                return this.milestone.edited.user_id === this.user.userId;
            },
            can_complete() {
                return this.user.role === 'contractor' && this.milestone.status === 'in_progress'
            }
        },
        methods: {
            backToPrevious(){
                this.$emit('getMilestone');
                const timer = setTimeout(() => {
                    this.$validator.validateAll();
                    clearTimeout(timer)
                },200)
            },
            showDialog(model){
                if (model === 'edit') {
                    this.edit_milestone_modal = true;
                    return;
                }
                this.dialog_content = this[model+'_milestone_dialog'];
                this.isDialogWithComOpen = true
            },
            submitDialog(comment){
                let type = this.dialog_content.activateName;
                this.dialog_comment = comment;
                switch(type){
                    case 'proposalConfDialog' :
                        this.editMilestone();
                        break;
                    case 'acceptEditDialog':
                        this.acceptMilestone();
                        break;
                    case 'rejectEditDialog':
                        this.rejectMilestone();
                        break;
                }
            },
            acceptMilestone() {
                this.dialog_loading = true;
                api.post('milestone.accept', {
                    milestone: this.milestone,
                    comment: this.dialog_comment,
                }).then(response => {
                    if (this.$route.query.status) {
                        this.$router.replace({'query': null});
                    }
                    this.isDialogWithComOpen = false;
                    this.dialog_loading = false;
                    this.$emit('getMilestone')
                })
            },
            rejectMilestone() {
                this.dialog_loading = true;
                api.post('milestone.reject', {
                    milestone: this.milestone,
                    comment: this.dialog_comment,
                }).then(response => {
                    if (this.$route.query.status) {
                        this.$router.replace({'query': null});
                    }
                    this.isDialogWithComOpen = false
                    this.dialog_loading = false;
                    this.$emit('getMilestone')
                    this.$router.replace({'query': null});

                })
            },
            editMilestone(comment) {
                this.dialog_loading = true;
                this.$store.state.global_loader = true;

                api.post('milestone.edit', {
                    milestone: this.milestone,
                    comment: comment,
                }).then(response => {
                    this.edit_milestone_modal = false;
                    this.dialog_loading = false;
                    this.$emit('getMilestone');
                })
            },
            toInvoice() {
                if (this.$route.name === 'current-milestone') {
                    return this.$router.push({
                        name: 'current-invoice',
                        params: {
                            guarantee_project_id: this.$route.params.guarantee_project_id,
                        }
                    })
                }
                return this.$router.push({
                    name: 'history-invoice',
                    params: {
                        guarantee_project_id: this.$route.params.guarantee_project_id,
                        invoice_id: this.milestone.invoice.id,
                    }
                });
            },
            back(){
                if (this.$route.params.version) {
                    return this.$router.push({
                        name: 'by-id-milestone',
                        params: {
                            guarantee_project_id: this.$route.params.guarantee_project_id,
                            milestone_id: this.$route.params.milestone_id,
                        }
                    })
                }
                this.$emit('getMilestone');
            }

        },
    }
</script>
