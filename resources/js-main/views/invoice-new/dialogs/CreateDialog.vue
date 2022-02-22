<template>
  <v-dialog light max-width="556" v-model="show" content-class="main-dialog__scroll-content scrollbar">
    <div class="main-dialog main-dialog_open">

      <div class="main-dialog__header">
        <img class="close-dialog" src="/img/icon/close-modal_gray.svg" @click="close">
        <h5 class="main-dialog__header-title">Send Payment Request</h5>
      </div>

      <div class="main-dialog__body">
        <p class="main-dialog__p">
          You are sending a Payment Request for Milestone {{milestone_sequence}}.
          Client will be notified, and will send payment directly to you.
        </p>
        <p class="main-dialog__p">
          Then, you must confirm receiving payment, and once confirmed, the
          Project Scope platform will automatically close this Milestone {{milestone_sequence}}.
        </p>
        <p class="main-dialog__p">
          Please provide a reason with your payment request.
        </p>
        <div class="main-dialog__form-group">
          <label class="main-dialog__label">Reason for Payment Request (required)</label>
          <textarea
            @keyup="resizeTextarea"
            @keydown="resizeTextarea"
            v-model="comment"
            placeholder="(ex.) Milestone completed as per contract"
            class="main-dialog__textarea"
          >
          </textarea>
          <div class="invalid-message">
            This field required
          </div>
        </div>
      </div>

      <div class="main-dialog__footer">
        <div class="main-dialog__footer-btn-row">
          <button class="main-btn main-btn_border-red" @click="close">
            CANCEL
          </button>
          <button :disabled="loading" class="main-btn main-btn_full-blue" @click="create">
            <template v-if="loading">
              <v-progress-circular indeterminate color="white"/>
            </template>
            <template v-else>
              {{create_text}}
            </template>
          </button>
        </div>
      </div>
    </div>
  </v-dialog>

</template>
<script>
  import close_dialog from '@/components/mixins/dialog/close_dialog';
  import {resizeTextarea} from '@/components/common/Mixins/textarea'

  export default {
    mixins: [close_dialog, resizeTextarea],
    props: ['milestone_sequence', 'create_text', 'loading'],
    data() {
      return {
        comment: ''
      }
    },
    methods: {
      create() {
        var el = $('.main-dialog__textarea');
        el.removeClass('invalid-input');

        if (!this.comment.length) {
          return el.addClass('invalid-input');
        }

        return this.$emit('createInvoice', this.comment)
      }
    }
  }
</script>
