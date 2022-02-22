<template>
  <div>
    <v-dialog content-class="main-dialog__scroll-content scrollbar" v-model="dialogMain" light max-width="556">
      <div class="main-dialog main-dialog_open">

        <div class="main-dialog__header">
          <img src="/img/icon/close-modal_gray.svg" @click="closeDialog" class="close-dialog">
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
            <div class="invalid-message">This field required</div>
          </div>
        </div>

        <div class="main-dialog__footer">
          <div class="main-dialog__footer-btn-row">
            <button class="main-btn main-btn_border-red" @click="closeDialog">
              {{currentDialog.cancelBtn}}
            </button>
            <button class="main-btn main-btn_full-blue"
                    @click="dialogAction(currentDialog.activateName)"
                    :disabled="loading">
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

    <accept-dialog
      v-model="dialog_confirm"
      :loading="loading"
      :milestone_sequence="invoice.milestone.sequence"
      @confirm="confirmInvoice"
    />

    <complete-dialog v-model="dialog_complete" :loading="loading" @complete="completeInvoice"/>

    <div ref="contractHeader" class="contract-comp__header">
      <div class="g-flex g-flex_row g-flex_wrap margin-10 g-flex_vertical-center">

        <template v-if="invoice.status === 'completed'">
          <div data-v-step="ciho-1" class="contract-header__title hidden-sm-and-down">
            Payment request {{invoice.milestone.sequence}}
            <span>Completed {{ invoice.created_at }}</span>
          </div>
          <div class="contract-header__contract-label">
            Completed
          </div>
          <v-spacer class="hidden-md-and-down"></v-spacer>
          <button class="main-btn main-btn_border-blue-deep hidden-md-and-down" @click="exportInvoice">
            <template v-if="download_pdf_loading === true">
              <v-progress-circular indeterminate color="#1875F0"/>
            </template>
            <template v-else>
              Download PDF
            </template>
          </button>
        </template>

        <template v-if="user.role === 'homeowner'">

          <template v-if="invoice.status === 'pending' || invoice.status === 'unverified'">

            <div data-v-step="ciho-1" class="contract-header__title hidden-sm-and-down">
              Payment request {{invoice.milestone.sequence}}
              <span>Created {{ invoice.created_at }}</span>
            </div>

            <div
              class="contract-header__contract-label contract-label_orange hidden-md-and-down"
              v-if="invoice.status === 'unverified'"
            >
              Confirmation Declined
            </div>
            <v-spacer class="hidden-md-and-down ciho-7-class"></v-spacer>

            <button class="main-btn main-btn_border-red" @click="showDialog('dialogReject')">
              {{rejectedText}}
            </button>
            <v-spacer class="hidden-md-and-up"></v-spacer>

            <button class="main-btn main-btn_full-green ciho-5-class ciho-6-class" @click="dialog_confirm = true">
              ACCEPT
            </button>

            <button class="main-btn main-btn_border-blue-deep hidden-md-and-down" @click="exportInvoice">
              <template v-if="download_pdf_loading === true">
                <v-progress-circular indeterminate color="#1875F0"/>
              </template>
              <template v-else>
                Download PDF
              </template>
            </button>
          </template>

          <template v-if="invoice.status === 'confirmed'">
            <div data-v-step="ciho-1" class="contract-header__title hidden-sm-and-down">
              Payment request {{invoice.milestone.sequence}}
              <span>Created {{ invoice.created_at }}</span>
            </div>
            <div class="contract-header__contract-label">
              Waiting for Pro to verify payment
            </div>
            <v-spacer class="hidden-md-and-down"></v-spacer>
            <button class="main-btn main-btn_border-blue-deep hidden-md-and-down" @click="exportInvoice">
              <template v-if="download_pdf_loading === true">
                <v-progress-circular indeterminate color="#1875F0"/>
              </template>
              <template v-else>
                Download PDF
              </template>
            </button>
          </template>

        </template>

        <template v-if="user.role === 'contractor'">

          <template v-if="invoice.status === 'pending' || invoice.status === 'unverified'">
            <div data-v-step="ciho-1" class="contract-header__title hidden-sm-and-down">
              Payment request {{invoice.milestone.sequence}}
              <span>Created {{ invoice.created_at }}</span>
            </div>
            <div class="contract-header__contract-label">
              Waiting for confirmation
            </div>
            <v-spacer class="hidden-md-and-down"></v-spacer>
            <v-spacer class="hidden-md-and-up"></v-spacer>
            <button class="main-btn main-btn_border-green hidden-md-and-down" @click="dialog_complete = true">
              CONFIRM PAYMENT
            </button>
            <v-spacer class="hidden-md-and-up"></v-spacer>
            <v-menu class="hidden-md-and-down" offset-y left nudge-top="-16">
              <button class="main-btn contract-header__dropmenu-box main-btn_border-blue" slot="activator">
                <span class="dropmenu-box__dots"></span>
                MORE
              </button>

              <div class="contract-header__drop-target">
                <ul class="option-list-dropdown">
                  <li class="option-list-dropdown__el" @click="showDialog('dialogReject')">
                    <img src="/img/icon/close_blue.svg" alt="history">
                    <span>Decline Payment request</span>
                  </li>
                  <li class="option-list-dropdown__el" @click="exportInvoice">
                    <img src="/img/icon/pdf-icon_blue.svg" alt="post-job">
                    <span>Download PDF</span>
                  </li>
                </ul>
              </div>
            </v-menu>
          </template>

          <template v-if="invoice.status === 'confirmed'">
            <div data-v-step="ciho-1" class="contract-header__title hidden-sm-and-down">
              Payment request {{invoice.milestone.sequence}}
              <span>Created {{ invoice.created_at }}</span>
            </div>
            <v-spacer class="hidden-md-and-down"></v-spacer>

            <button class="main-btn main-btn_border-red" @click="showDialog('dialogUnverify')">
              {{rejectConfirmationText}}
            </button>
            <v-spacer class="hidden-md-and-up"></v-spacer>

            <button class="main-btn main-btn_full-green" @click="dialog_complete = true">
              {{verifyPaymentText}}
            </button>

            <button class="main-btn main-btn_border-blue-deep hidden-md-and-down" @click="exportInvoice">
              <template v-if="download_pdf_loading === true">
                <v-progress-circular indeterminate color="#1875F0"/>
              </template>
              <template v-else>
                Download PDF
              </template>
            </button>
          </template>
        </template>
      </div>
    </div>
  </div>
</template>
<script>
  import {currentDialog, dialogReject, dialogUnverify} from '@/components/dialog/invoiceCurrent'
  import {mapGetters} from 'vuex';

  import {resizeTextarea} from '@/components/common/Mixins/textarea'
  import export_invoice from '@/components/mixins/export/invoice'

  import AcceptDialog from './dialogs/AcceptDialog'
  import CompleteDialog from './dialogs/CompleteDialog'

  export default {
    mixins: [resizeTextarea, export_invoice],
    components: {
      'accept-dialog': AcceptDialog,
      'complete-dialog': CompleteDialog,
    },
    data() {
      return {
        download_pdf_loading: false,
        loading: false,
        dialogMain: false,
        dialogComment: '',
        currentDialog: currentDialog,
        confirmComment: '',
        dialogReject: dialogReject,
        dialogUnverify: dialogUnverify,
        payment_details: {
          comment: '',
          files: []
        },
        dialog_confirm: false,
        // dialog_complete: false,
      }
    },
    props: {
      invoice: Object,
    },
    computed: {
      ...mapGetters(["user", "guarantee_project", "current_milestone"]),
      dialog_complete: {
        get() {
          return this.$store.state.invoiceCurrent.dialogComplete
        },
        set(val) {
          this.$store.state.invoiceCurrent.dialogComplete = val;
        }
      },
      dialogRejectMobile: {
        get() {
          return this.$store.state.invoiceCurrent.dialogReject
        },
        set(val) {
          if (val === true) {
            this.showDialog('dialogReject');
          } else {
            this.closeDialog()
          }
          return this.$store.commit('set', {type: 'invoiceCurrent', data: {dialogReject: val}})
        }
      },
      mobileAndTabletState() {
        return window.innerWidth >= 992
      },
      rejectConfirmationText() {
        return window.innerWidth >= 600 ? 'DECLINE' : 'DECLINE'
      },
      verifyPaymentText() {
        return window.innerWidth >= 600 ? ' VERIFY PAYMENT' : ' VERIFY'
      },
      rejectedText() {
        return window.innerWidth < 992 && window.innerWidth >= 600 ? 'DECLINE INVOICE' : 'DECLINE';
      },
      rejectedConfirmText() {
        return window.innerWidth < 992 && window.innerWidth >= 600 ? 'CONFIRM PAYMENT' : 'CONFIRM';
      },
    },
    watch: {
      // 'dialogCompleteMobile': function (val) {
      //     console.log(1);
      //     if (val === true) {
      //         this.showDialog('dialogComplete');
      //     }
      // },
      'dialogRejectMobile': function (val) {
        if (val === true) {
          this.showDialog('dialogReject');
        }
      },
      'dialogMain': function (val) {
        if (val === false) {
          this.dialogCompleteMobile = false
          this.dialogRejectMobile = false
        }
      },
    },
    methods: {
      showDialog(model) {
        this.dialogMain = true
        this.currentDialog = this[model];
      },
      closeDialog() {
        this.dialogMain = false
        this.dialogComment = ''
        $('.main-dialog__textarea').removeClass('invalid-input')
      },
      dialogAction(modelName) {
        this.loading = true
        switch (modelName) {
          case 'dialogReject':
            this.rejectInvoice()
            break;
          case 'dialogUnverify':
            this.unverifyInvoice()
            break;
        }
      },
      checkRequiredComment(value) {
        let state = this[value].length > 0;
        if (value === 'confirmComment') {
          state = this.payment_details.comment.length > 0;
        }
        if (!state) {
          $('.main-dialog__textarea').addClass('invalid-input');
        } else {
          $('.main-dialog__textarea').removeClass('invalid-input');
        }
        return state
      },
      rejectInvoice() {
        if (this.checkRequiredComment('dialogComment')) {
          api.post('invoice.reject', {
            invoice_id: this.invoice.id,
            comment: this.dialogComment
          })
            .then(response => {
              this.loading = false
              this.dialogComment = '';
              this.dialogMain = false;
              this.toMessage();
            })
        }
      },
      confirmInvoice(payment_details) {

        var params = {
          invoice_id: this.invoice.id,
          payment_details: payment_details
        };

        this.loading = true;
        api.post('invoice.confirm', params).then(response => {
          this.loading = false
          this.toMessage()
        });
      },
      unverifyInvoice() {
        if (this.checkRequiredComment('dialogComment')) {
          api.post('invoice.unverify', {
            invoice_id: this.invoice.id,
            comment: this.dialogComment

          }).then(response => {
            this.loading = false
            this.dialogComment = '';
            this.dialogMain = false;
            this.toMessage()
          });
        }
      },
      completeInvoice(comment) {
        let params = {
          invoice_id: this.invoice.id,
          comment: comment
        };
        this.loading = true;

        api.post('invoice.complete', params).then(response => {
          this.loading = false
          this.toMessage()
        });
      },
      toMessage() {
        this.$router.push({
          name: 'messages',
          params: {
            guarantee_project_id: this.$route.params.guarantee_project_id
          }
        })
      },
      toCreateInvoice() {
        this.$router.push({
          name: 'create-invoice',
          params: {
            guarantee_project_id: this.$route.params.guarantee_project_id
          }
        })
      },
    },
  }
</script>
