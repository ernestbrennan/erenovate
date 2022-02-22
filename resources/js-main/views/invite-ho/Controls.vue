<template>
  <div>
    <create-draft-dialog v-model="dialog_create_draft" :loading="loading" @create="createDraft"/>

    <save-draft-dialog v-model="dialog_save_draft" :loading="loading" @save="saveArchivedDraft"/>

    <cancel-creation-dialog v-model="dialog_cancel_creation"/>

    <div ref="contractHeader" class="contract-comp__header">
      <div class="g-flex g-flex_row g-flex_wrap margin-10" data-v-step="ccd-3">

        <div class="contract-header__title hidden-sm-and-down" data-v-step="ccd-2">
          Project Scope Draft Creation
        </div>

        <v-spacer class="hidden-sm-and-down"></v-spacer>
        <button class="main-btn main-btn_border-red" @click="dialog_cancel_creation = true" data-v-step="ccd-6">
          CANCEL
        </button>

        <v-spacer class="hidden-md-and-up"></v-spacer>
        <button class="main-btn main-btn_full-blue" @click="saveOrUpdateArchivedDraft">
          {{ this.text_save_button }}
        </button>

        <v-spacer class="hidden-md-and-up"></v-spacer>
        <button class="main-btn main-btn_full-green" data-v-step="ccd-1"
                @click="validateContract('dialog_create_draft')">
          {{ this.text_create_button }}
        </button>

      </div>
    </div>

    <template v-if="getTour">
      <v-tour name="tourCreateContract" :steps="steps" :options="optionsVueTour" :callbacks="tourCallbacks"/>
    </template>
  </div>
</template>
<script>
  import {mapGetters} from 'vuex';

  import TourInit from '@/plugins/tour/init';
  import CreateContract from '@/plugins/tour/createContract';
  import child_validator from '@/components/mixins/validator/base/child_validator';
  import contract_builder_validator from '@/components/mixins/validator/contract_builder';

  import CreateDialog from './dialogs/CreateDialog';
  import SaveDialog from './dialogs/SaveDialog';
  import CancelCreationDialog from './dialogs/CancelCreationDialog';

  import {inviteHOProject} from '@/api/project'
  import {saveArchivedDraft, updateArchivedDraft} from "@/api/archived-draft";

  export default {
    mixins: [child_validator, contract_builder_validator, TourInit, CreateContract],
    components: {
      'create-draft-dialog': CreateDialog,
      'save-draft-dialog': SaveDialog,
      'cancel-creation-dialog': CancelCreationDialog
    },
    data() {
      return {
        dialog_create_draft: false,
        dialog_save_draft: false,
        dialog_cancel_creation: false,
        loading: false,
      }
    },
    computed: {
      ...mapGetters(["user", "contract_draft", "guarantee_project", "contract"]),

      text_create_button() {
        return window.innerWidth > 576 ? 'SEND PROJECT SCOPE' : 'SEND'
      },
      text_save_button() {
        return window.innerWidth > 576 ? 'SAVE DRAFT' : 'SAVE'
      },
      text_cancel_button() {
        return window.innerWidth > 576 ? ' CANCEL DRAFT' : 'CANCEL'
      },
    },
    methods: {

      toMessages() {
        this.$router.push({
          name: 'messages',
          params: {
            guarantee_project_id: this.guarantee_project.id
          }
        })
      },

      saveOrUpdateArchivedDraft() {
        if (this.$route.params.archived_draft_id) {
          return this.updateArchivedDraft();
        }

        this.dialog_save_draft = true
      },

      saveArchivedDraft(title) {
        this.loading = true;

        var params = {
          contract: this.contract,
          contract_draft: this.contract_draft,
          title: title
        };

        saveArchivedDraft(params).then(response => {
          this.loading = false;
          this.dialog_create_draft = false;

          this.$router.push({name: 'archived-drafts-list'})

        }).catch(error => {
          this.loading = false;
          this.dialog_create_draft = false;
        });
      },
      updateArchivedDraft() {
        this.loading = true;

        var params = {
          contract: this.contract,
          contract_draft: this.contract_draft,
          id: this.$route.params.archived_draft_id,
        };

        updateArchivedDraft(params).then(response => {
          this.loading = false;

          this.$store.commit('set', {
            type: 'errorAlert',
            data: {
              type: 'info',
              text: "Project Scope Draft has been saved"
            }
          });

        }).catch(error => {
          this.loading = false;
          this.$store.commit('set', {
            type: 'errorAlert',
            data: {
              type: 'error',
              text: 'Project Scope Draft has not been saved'
            }
          });

        });
      },

      createDraft(comment) {
        this.loading = true;

        var params = {
          contract: this.contract,
          contract_draft: this.contract_draft,
          comment: comment,
          archived_draft_id: this.$route.params.archived_draft_id
        };

        inviteHOProject(params).then(response => {
          this.loading = false;
          this.dialog_create_draft = false;

          localStorage.setItem('is_visited_' + response.data.guarantee_project.id, true);

          this.$store.state.guarantee_project = response.data.guarantee_project;

          this.toMessages();

        }).catch(error => {
          this.loading = false;
          this.dialog_create_draft = false;

          let text = '';

          if (error.message.role_contractor) {

            text = `User with email ${this.contract.invite_info.email} exists`;
          }
          this.$store.commit('set', {
            type: 'errorAlert',
            data: {
              type: 'error',
              text: text
            }
          });
        });
      },
    },
  }
</script>

<style scoped>
  .contract-header__title {
    line-height: 40px;
    font-size: 24px;
  }
</style>
