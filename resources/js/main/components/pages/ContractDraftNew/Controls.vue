<template>
    <div>
        <create-draft-dialog v-model="dialog_create_draft" :loading="loading" @create="create"/>

        <cancel-creation-dialog v-model="dialog_cancel_creation"/>

        <div ref="contractHeader" class="contract-comp__header">
            <div class="g-flex g-flex_row g-flex_wrap margin-10" data-v-step="ccd-3">

                <div class="contract-header__title hidden-sm-and-down" data-v-step="ccd-2">
                    Project Scope Draft Creation
                </div>

                <v-spacer class="hidden-sm-and-down"></v-spacer>
                <button class="main-btn main-btn_border-red" @click="dialog_cancel_creation = true" data-v-step="ccd-6">
                    {{ this.cancelPros }}
                </button>

                <v-spacer class="hidden-md-and-up"></v-spacer>
                <button class="main-btn main-btn_full-green"
                        data-v-step="ccd-1"
                        @click="validateContract('dialog_create_draft')">
                    {{ this.createPros }}
                </button>

            </div>
        </div>

        <v-tour v-if="getTour" name="tourCreateContract" :steps="steps" :options="optionsVueTour" :callbacks="tourCallbacks" />

    </div>
</template>
<script>
    import {mapGetters} from 'vuex'
    import TourInit from '../../../plugins/tour/init'
    import CreateContract from '../../../plugins/tour/createContract'

    import child_validator from '../../mixins/validator/base/child_validator';
    import contract_builder_validator from '../../mixins/validator/contract_builder';

    import CancelCreationDialog from './dialogs/CancelCreationDialog'
    import CreateDraftDialog from './dialogs/CreateDialog'

    export default {
        mixins: [child_validator, contract_builder_validator, TourInit, CreateContract],
        components: {
            'create-draft-dialog': CreateDraftDialog,
            'cancel-creation-dialog': CancelCreationDialog
        },
        data() {
            return {
                loading: false,
            }
        },
        computed: {
            ...mapGetters(["user" ,"contract_draft", "guarantee_project", "contract"]),
            createPros() {
                return window.innerWidth > 576 ? 'SEND PROJECT SCOPE' : 'CREATE'
            },

            cancelPros() {
                return window.innerWidth > 576 ? ' CANCEL' : 'CANCEL'
            },

            dialog_cancel_creation: {
                get() {
                    return this.$store.state.contractNewDraftDialog.CancelCreation
                },
                set(val) {
                    if(val === false){
                        this.$store.commit('set',{'type': 'routerRedirect',data:false})
                    }
                    return this.$store.commit('newDraftDialog', {type: 'CancelCreation', data: val})
                }
            },
            dialog_create_draft: {
                get() {
                    return this.$store.state.contractNewDraftDialog.ContractCreate
                },
                set(val) {
                    return this.$store.commit('newDraftDialog', {type: 'ContractCreate', data: val})
                }
            },

        },
        methods: {

            create(comment) {
                this.loading = true;

                var params = {
                    contract: this.contract,
                    contract_draft: this.contract_draft,
                    comment: comment
                };

                api.post('contract-draft.newDraft', params).then(response => {

                    this.loading = false;

                    this.$router.push({
                        name: 'messages',
                        params: {
                            guarantee_project_id: this.guarantee_project.id
                        }
                    })
                });
            },
        },
    }
</script>
