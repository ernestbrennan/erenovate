<template>
  <v-dialog light max-width="556" v-model="show" content-class="main-dialog__scroll-content scrollbar">
    <div class="main-dialog main-dialog_open">

      <div class="main-dialog__header">
        <img class="close-dialog" src="/img/icon/close-modal_gray.svg" @click="close">
        <h5 class="main-dialog__header-title">Accept & Confirm Payment Request</h5>
      </div>

      <div class="main-dialog__body">
        <p class="main-dialog__p">
          As the client, I accept the Payment Request for this Milestone {{milestone_sequence}},
          and confirm I sent payment.
        </p>
        <p class="main-dialog__p">
          To confirm your payment, submit proof of payment made directly to the Bonded Pro’s business.
        </p>
        <p class="main-dialog__p">
          Enter payment details from your financial institution (Transaction #),
          and upload supporting documents (Ex. email transfer screenshot)
        </p>
        <div class="main-dialog__form-group">
          <label class="main-dialog__label">Payment Details.</label>
          <textarea
            @keyup="resizeTextarea"
            @keydown="resizeTextarea"
            v-model="payment_details.comment"
            placeholder="Provide brief description of payment transaction (required)"
            class="main-dialog__textarea main-dialog__textarea_dropzone"
            name="Comment reject payment"
          >
          </textarea>
          <div class="invalid-message">This field required</div>
        </div>
        <div class="main-dialog__form-group">
          <label class="main-dialog__label">Attach Supporting Documents (recommended)</label>
          <file-attachments-dialog :files="payment_details.files" ref="payment_dropzone"/>
        </div>
      </div>

      <div class="main-dialog__footer">
        <div class="main-dialog__footer-btn-row">
          <button class="main-btn main-btn_border-red" @click="close">
            CANCEL
          </button>
          <button :disabled="loading" class="main-btn main-btn_full-blue" @click="confirm">
            <template v-if="loading === true">
              <v-progress-circular indeterminate color="white"/>
            </template>
            <template v-else>
              CONFIRM PAYMENT
            </template>
          </button>
        </div>
      </div>
    </div>
  </v-dialog>
</template>

<script>
  import close_dialog from '@/components/mixins/dialog/close_dialog'
  import {resizeTextarea} from '@/components/common/Mixins/textarea'

  import FileAttachmentsDialog from '@/components/common/FileAttachmentsDialog';

  export default {
    mixins: [close_dialog, resizeTextarea],
    components: {
      'file-attachments-dialog': FileAttachmentsDialog,
    },
    props: ['loading', 'milestone_sequence'],
    data() {
      return {
        payment_details: {
          comment: '',
          files: []
        }
      }
    },
    methods: {
      confirm() {
        var comment = $('.main-dialog__textarea');
        var dropzone = $(this.$refs.payment_dropzone.$el);

        comment.removeClass('invalid-input');
        dropzone.removeClass('empty_dropzone');

        if (this.payment_details.comment.length || this.payment_details.files.length) {
          return this.$emit('confirm', this.payment_details);
        }

        comment.addClass('invalid-input');
        dropzone.addClass('empty_dropzone');
      }
    }
  }
</script>
