<template>
  <div>
    <cancel-creation-dialog v-model="dialog_cancel_creation" :milestone_sequence="invoice.milestone.sequence"/>

    <create-invoice-dialog
      :milestone_sequence="invoice.milestone.sequence"
      v-model="dialog_invoice_create"
      :create_text="create_text"
      :loading="loading"
      @createInvoice="createInvoice"
    />


    <div ref="contractHeader" class="contract-comp__header">
      <div class="g-flex g-flex_row g-flex_wrap margin-10 g-flex_vertical-center">
        <div class="contract-header__title hidden-sm-and-down" data-v-step="ci-1">
          Payment request for Milestone {{invoice.milestone.sequence}}
        </div>
        <v-spacer class="hidden-md-and-down"></v-spacer>
        <button class="main-btn main-btn_border-red" @click="dialog_cancel_creation = true">
          {{cancel_text}}
        </button>
        <v-spacer class="hidden-md-and-up"></v-spacer>
        <button data-v-step="ci-2" class="ci-8-class main-btn main-btn_full-green" @click="validateInvoice">
          {{create_text}}
        </button>
      </div>
    </div>
    <template v-if="get_tour">
      <v-tour name="tourInvoice" :steps="steps" :options="optionsVueTour" :callbacks="tourCallbacks"/>
    </template>
  </div>
</template>
<script>
  import {mapGetters} from 'vuex'
  import TourInvoice from '@/plugins/tour/invoiceCreate'

  import child_validator from "@/components/mixins/validator/base/child_validator"

  import CancelCreationDialog from './dialogs/CancelCreationDialog';
  import CreateInvoiceDialog from './dialogs/CreateDialog';

  export default {
    mixins: [child_validator, TourInvoice],
    components: {
      'cancel-creation-dialog': CancelCreationDialog,
      'create-invoice-dialog': CreateInvoiceDialog
    },
    data() {
      return {
        loading: false,
        createComment: '',
        updateMode: false,
        guarantee_project_id: this.$route.params.guarantee_project_id
      }
    },
    props: {
      invoice: Object,
    },
    computed: {
      ...mapGetters(["user"]),
      get_tour() {
        return window.innerWidth >= 1200
      },
      dialog_invoice_create: {
        get() {
          return this.$store.state.invoiceCreate.dialogCreate
        },
        set(val) {
          return this.$store.commit('set', {type: 'invoiceCreate', data: {dialogCreate: val}})
        }
      },
      dialog_cancel_creation: {
        get() {
          return this.$store.state.invoiceCreate.dialogCancel
        },
        set(val) {
          return this.$store.commit('set', {type: 'invoiceCreate', data: {dialogCancel: val}})
        }
      },
      create_text() {
        let string = ''
        if (this.updateMode) {
          string = (window.innerWidth >= 600) ? 'UPDATE PAYMENT REQUEST' : 'UPDATE'
        } else {
          string = (window.innerWidth >= 600) ? 'SEND PAYMENT REQUEST' : 'SEND'
        }
        return string;
      },
      cancel_text() {
        let string = ''
        if (this.updateMode) {
          string = (window.innerWidth >= 600) ? 'CANCEL UPDATE' : 'CANCEL'
        } else {
          string = (window.innerWidth >= 600) ? 'CANCEL CREATION' : 'CANCEL'
        }
        return string;
      },
    },
    methods: {
      validateInvoice() {
        let valInvoice = false;
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
            this.dialog_invoice_create = true
            valInvoice = true
          }
          if (!result) {
            valInvoice = false
          }
        });
        if (!valInvoice) {
          setTimeout(() => {
            if (!valInvoice) {
              var container = $('.contract-comp__body'),
                scrollTo = $('.contract-comp__body .scroll__invalid-input').eq(0);
              if (typeof scrollTo !== 'undefined') {
                container.animate({
                  scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop() - 200
                }, 1000);
              }
            }
          }, 500)
        }
      },
      createInvoice(comment) {
        this.loading = true;

        let params = {
          invoice: this.invoice,
          milestone_id: this.milestone_id,
          comment: comment
        };

        api.post('invoice.create', params)
          .then(response => {
            this.loading = false;
            this.dialog_invoice_create = false;

            this.$router.push({
              name: 'list-invoice',
              params: {guarantee_project_id: this.guarantee_project_id}
            })
          });
      }
    },
  }
</script>
