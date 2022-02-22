<template>
  <div>
    <!--template dialog start-->
    <v-dialog light max-width="556" v-model="dialogMain" content-class="main-dialog__scroll-content scrollbar">

      <div class="main-dialog main-dialog_open">

        <div class="main-dialog__header">
          <img @click="closeDialog" class="close-dialog" src="/img/icon/close-modal_gray.svg">
          <h5 class="main-dialog__header-title">{{currentDialog.title}}</h5>
        </div>
        <div class="main-dialog__body">
          <div class="main-dialog__text-box" v-html="currentDialog.text"></div>
          <div
            v-if="typeof currentDialog.comment !== 'undefined' || currentDialog.comment !== false"
            class="main-dialog__form-group"
          >
            <label class="main-dialog__label">{{currentDialog.comment.label}}</label>
            <textarea
              @keyup="resizeTextarea"
              @keydown="resizeTextarea"
              v-model="dialogComment"
              :placeholder="currentDialog.comment.placeholder"
              class="main-dialog__textarea"
              @blur="checkRequiredComment('dialogComment')"
            >
            </textarea>
            <div class="invalid-message">
              This field required
            </div>
          </div>
        </div>

        <div class="main-dialog__footer">
          <div class="main-dialog__footer-btn-row">
            <button class="main-btn main-btn_border-red" @click="closeDialog">
              {{currentDialog.cancelBtn}}
            </button>
            <button
              class="main-btn main-btn_full-blue"
              @click="dialogAction(currentDialog.activateName)"
              :disabled="loading"
            >
              <template v-if="loading === true">
                <v-progress-circular indeterminate color="white"/>
              </template>
              <template v-else>
                {{currentDialog.submitBtn}}
              </template>
            </button>
          </div>
        </div>
      </div>
    </v-dialog>

    <cancel-creation-dialog v-model="dialog_cancel_creation"/>
    <pay-dialog v-if="payFeeState" :contract_draft_id="contract_draft.id" :amount="feeAmount" v-model="dialog_pay_fee"/>

    <div ref="contractHeader" class="contract-comp__header">
      <div class="mobile-align-center g-flex g-flex_row g-flex_wrap margin-10">

        <template v-if="propose">
          <div class="contract-header__title hidden-sm-and-down">
            {{ 'Editing Project ' + project_name }}
          </div>
          <v-spacer class="hidden-sm-and-down"></v-spacer>
          <button class="main-btn main-btn_border-red" @click="dialog_cancel_creation = true">
            {{ this.cancelPropose }}
          </button>
          <v-spacer class="hidden-md-and-up"></v-spacer>
          <button class="main-btn main-btn_full-green" @click="validateContract('dialogContractPropose')">
            {{ this.createPros }}
          </button>
        </template>

        <template v-else>
          <div class="contract-header__title hidden-sm-and-down">
            {{ project_name }} Project Draft
          </div>

          <button class="contract-header__contract-label"
                  v-if="(user.userId === contract_draft.user_id || contract_draft.current_accepted) && !contract_draft.interlocutor_accepted">
            Waiting for {{ contract.interlocutor_info.user.role | capitalize }} to Accept Project
          </button>

          <label class="contract-header__contract-label"
                 v-else-if="contract_draft.current_accepted && contract_draft.interlocutor_accepted && user.role === 'homeowner'">
            Project is under review
          </label>

          <template v-if="payFeeState">
            <label class="contract-header__contract-label hidden-md-and-down">
              Waiting for payment
            </label>
            <label class="contract-header__contract-label-mobile-left">
              Waiting for payment
            </label>
          </template>

          <v-spacer class="hidden-md-and-up"></v-spacer>
          <v-spacer class="hidden-sm-and-down"></v-spacer>

          <template v-if="!(user.userId === contract_draft.user_id && !contract_draft.interlocutor_accepted)">

            <button v-if="contract_draft.status === 'pending'"
                    @click="showDialog('dialogRejectDraft')"
                    class="main-btn main-btn_border-red contract-header__reject-draft">
              {{rejectDraftText}}
            </button>

            <button class="main-btn main-btn_full-green"
                    v-if="!contract_draft.current_accepted "
                    @click="validateContract('dialogAcceptDraft')">
              {{acceptContractText}}
            </button>
          </template>

          <template v-if="payFeeState">
            <v-spacer class="hidden-md-and-up"></v-spacer>

            <button class="main-btn main-btn_full-green" @click="dialog_pay_fee = true">
              Pay Fee
            </button>
          </template>

          <drop-menu @proposeTerms="proposeTerms"/>

        </template>
      </div>
    </div>
  </div>
</template>
<script>
  import {mapGetters} from 'vuex'
  import {
    dialogContractPropose,
    currentDialog,
    dialogRejectDraft,
    dialogAcceptDraft,
    dialogProposalConfirm
  } from '@/components/dialog/contractDraftCurrent'
  import {resizeTextarea} from '@/components/common/Mixins/textarea'

  import child_validator from '@/components/mixins/validator/base/child_validator';

  import CancelCreationDialog from './dialogs/CancelCreationDialog'
  import PayDialog from './dialogs/PayDialog'

  import DropMenu from './parts/DropMenu'

  export default {
    components: {
      'cancel-creation-dialog': CancelCreationDialog,
      'pay-dialog': PayDialog,
      'drop-menu': DropMenu,
    },
    mixins: [child_validator, resizeTextarea],
    data() {
      return {
        currentDialog: currentDialog,
        dialogComment: '',
        dialogMain: false,
        dialogContractPropose: dialogContractPropose,
        dialogProposalConfirm: dialogProposalConfirm,
        dialogRejectDraft: dialogRejectDraft,
        dialogAcceptDraft: dialogAcceptDraft,

        dialog_pay_fee: false,
        dialog_cancel_creation: false,

        loading: false,
      }
    },
    computed: {
      ...mapGetters(["contracts", "user", "contract_draft", "guarantee_project", "contract", "draft_summary"]),

      createPros() {
        return window.innerWidth > 576 ? 'PROPOSE DIFFERENT TERMS' : 'PROPOSE'
      },

      cancelPropose() {
        return window.innerWidth > 576 ? 'CANCEL CREATION' : 'CANCEL'
      },

      acceptContractText() {
        return (window.innerWidth >= 600) ? 'ACCEPT DRAFT' : 'ACCEPT'
      },

      rejectDraftText() {
        return (window.innerWidth >= 600) ? 'DECLINE DRAFT' : 'DECLINE'
      },

      getRejectPlaceholder() {
        if (this.user.role === 'homeowner') {
          return 'Provide your reason to inform the Pro'
        } else {
          return 'Provide your reason to inform the client'
        }
      },
      project_name() {
        return (this.guarantee_project.project_name.length > 20) ? this.guarantee_project.project_name.substr(0, 17) + '...' : this.guarantee_project.project_name;
      },
      propose: {
        get() {
          return this.$store.state.currentContract.propose
        },
        set(val) {
          return this.$store.commit('set', {type: 'currentContract', data: {propose: val}})
        }
      },
      feeAmount() {
        return formatPrice(this.contract_draft.summary.service_cost) + formatPrice(this.contract_draft.summary.material_cost);
      },

      payFeeState() {
        return this.contract_draft.current_accepted && this.contract_draft.interlocutor_accepted && this.user.role === 'contractor'
      }
    },
    methods: {
      showDialog(model) {
        this.currentDialog = this[model];
        if (this.currentDialog.activateName === 'dialogContractPropose' || this.currentDialog.activateName === 'dialogAcceptDraft') {
          if (this.$store.state.hasNewBatch) {
            this.$store.commit('set', {type: 'errorAlert', data: {type: 'error', text: 'Please submit posted files'}});
            return false;
          }
        }
        if (this.currentDialog.activateName === 'dialogRejectDraft') {
          this.currentDialog.comment.placeholder = this.getRejectPlaceholder
        }
        this.dialogMain = true;
      },
      closeDialog() {
        this.dialogMain = false;
        this.dialogComment = '';
        $('.main-dialog__textarea').removeClass('invalid-input')
      },
      dialogAction(modelName) {
        switch (modelName) {
          case 'dialogContractPropose':
            this.proposeDraft();
            break;
          case 'dialogRejectDraft':
            this.rejectDraft();
            break;
          case 'dialogAcceptDraft':
            this.acceptDraft();
            break;
          case 'dialogProposalConfirm':
            this.proposeDraft();
            break;
        }
      },
      checkRequiredComment(value) {
        let state = this[value].length > 0;
        if (!state) {
          $('.main-dialog__textarea').addClass('invalid-input')
        } else {
          $('.main-dialog__textarea').removeClass('invalid-input')
        }
        return state
      },
      validateContract(modal) {
        let valConatract = false;

        if (modal === 'dialogAcceptDraft' && this.contract.interlocutor_info.status !== 'confirmed') {
          if (this.contract.interlocutor_info.status === 'new') {
            this.$store.commit('set', {
              type: 'errorAlert',
              data: {type: 'info', text: 'Wait for User Info'}
            })

          }
          if (this.contract.interlocutor_info.status === 'pending') {
            this.$store.commit('set', {
              type: 'errorAlert',
              data: {type: 'error', text: 'Confirm User Info'}
            })
          }
          return
        }
        this.$store.commit('validateDropZone');
        if (this.$store.state.dropZoneValidMap.valid === false) {
          this.$store.commit('set', {
            type: 'errorAlert',
            data: {type: 'error', text: 'Please, submit files with a comment and click [Upoad Attachment]'}
          });
          return false;
        }
        this.$validator.validateAll().then((result) => {
          if (result) {

            if (!this.contract_draft.contract_signed.file) {
              this.$store.commit('set', {
                type: 'errorAlert',
                data: {type: 'error', text: 'Please submit signed contract'}
              });
              var container = $('.contract-comp__body'),
                scrollTo = $('.contract-comp__body #signed_dropzone');
              if (typeof scrollTo !== 'undefined') {
                container.animate({
                  scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop() - 100
                }, 1000);
              }
              return false;
              valConatract = true
            } else {
              this.showDialog(modal)
              valConatract = true
            }
          }
          if (!result) {

            $('.contract-draft__slide-main-box').slideDown();
            $('.contract-draft__curret').removeClass('active');
            valConatract = false
          }
        });

        if (!valConatract) {

          setTimeout(() => {
            if (!valConatract) {
              var container = $('.contract-comp__body'),
                scrollTo = $('.contract-comp__body .scroll__invalid-input').eq(0);
              if (typeof scrollTo !== 'undefined') {
                container.animate({
                  scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop() - 200
                }, 1000);
              } else {
                this.$store.commit('set', {
                  type: 'errorAlert',
                  data: {type: 'error', text: 'Error!scrollTo: ' + scrollTo}
                })
              }
            }

          }, 500)
        }
      },
      toMessages() {
        this.$router.push({
          name: 'messages',
          params: {
            guarantee_project_id: this.guarantee_project.id
          }
        })
      },
      proposeDraft() {
        let state = false;
        state = this.checkRequiredComment('dialogComment')

        if (state) {
          this.loading = true;

          api
            .post('contract-draft.proposeDraft', {
              contract: this.contract,
              contract_draft: this.contract_draft,
              comment: this.dialogComment
            })
            .then(response => {
              this.loading = false
              this.toMessages()
            }).catch(error => {
            this.loading = false
          });
        }
      },
      rejectDraft() {
        let state = false
        state = this.checkRequiredComment('dialogComment')

        if (state) {
          this.loading = true;

          api.post('contract-draft.reject', {
            contract_draft_id: this.contract_draft.id,
            comment: this.dialogComment
          }).then(response => {

            this.loading = false
            this.toMessages()

          }).catch(error => {
            this.laoding = false
          });
        }
      },
      acceptDraft() {
        let state = false;
        state = this.checkRequiredComment('dialogComment');

        if (state) {
          this.loading = true;

          api.post('contract-draft.accept', {
            contract_draft_id: this.contract_draft.id,
            comment: this.dialogComment,
            user_info: this.contract.current_user_info

          }).then(response => {
            this.loading = false
            this.toMessages()

          }).catch(error => {
            this.loading = false
          });
        }
      },
      proposeTerms() {
        if (this.contract.interlocutor_info.status !== 'new' && this.contract.interlocutor_info.status === 'pending') {
          this.$store.commit('set', {type: 'errorAlert', data: {type: 'error', text: 'Confirm User Info'}})
          return;
        }

        this.propose = true;
        this.$store.commit('set', {type: 'readOnly', data: false});
      },
    },
    created() {
      this.propose = false;
    },
    beforeDestroy() {
      this.propose = false
    },
  }
</script>
