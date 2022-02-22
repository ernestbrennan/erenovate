<template>
  <v-dialog light max-width="556" v-model="show" content-class="main-dialog__scroll-content scrollbar">
    <div class="main-dialog main-dialog_open">
      <div class="main-dialog__header">
        <img class="close-dialog" src="/img/icon/close-modal_gray.svg" @click="close">
        <h5 class="main-dialog__header-title">
          Cancel the Project?
        </h5>
      </div>

      <div class="main-dialog__body">
        <p class="main-dialog__p">
          Do you wish to cancel your project?
        </p>
        <p class="main-dialog__p">
          To cancel the project, please indicate the reason below.
        </p>
        <p class="main-dialog__p">
          <b>Note: </b> This action is permanent There is no undo.
        </p>
        <div class="main-dialog__form-group">
          <label class="main-dialog__label">Reason for Canceling</label>
          <textarea
            placeholder="Provide your reason so to better serve you next time"
            class="main-dialog__textarea"
            @keydown="resizeTextarea"
            @keyup="resizeTextarea"
            v-model="comment"
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
          <button :disabled="loading" class="main-btn main-btn_full-blue" @click="withdraw">
            <template v-if="loading">
              <v-progress-circular indeterminate color="white"/>
            </template>
            <template v-else>
              {{text_withdraw_button}}
            </template>
          </button>
        </div>
      </div>
    </div>
  </v-dialog>
</template>
<script>
  import {resizeTextarea} from '@/components/common/Mixins/textarea'
  import close_dialog from '@/components/mixins/dialog/close_dialog'

  export default {
    mixins: [resizeTextarea, close_dialog],
    data() {
      return {
        comment: ''
      }
    },
    props: {
      loading: Boolean
    },
    computed: {
      text_withdraw_button() {
        return window.innerWidth >= 768 ? 'WITHDRAW PROJECT' : 'OK'
      },
    },
    methods: {
      withdraw() {
        var el = $('.main-dialog__textarea');

        if (!this.comment.length) {
          return el.addClass('invalid-input')
        }

        el.removeClass('invalid-input');

        return this.$emit('withdraw', this.comment);
      }
    },
  }
</script>
