<template>
  <v-dialog light max-width="556" v-model="show" content-class="main-dialog__scroll-content scrollbar">
    <div class="main-dialog main-dialog_open">
      <div class="main-dialog__header">
        <img @click="close" class="close-dialog" src="/img/icon/close-modal_gray.svg">
        <h5 class="main-dialog__header-title">Submit Milestone Edit…</h5>
      </div>
      <div class="main-dialog__body">

        <div class="main-dialog__text-box">
          <p class="main-dialog__p">
            You are about to propose milestone edits, which will be reviewed by the
            {{ user.role === 'homeowner' ? 'PRO' : 'Client'}}.
            We will notify you once proposed edits are accepted or declined.
          </p>
        </div>

        <div class="main-dialog__form-group">
          <label class="main-dialog__label">Enter reason for the edit (required)</label>
          <textarea
            @keyup="resizeTextarea"
            @keydown="resizeTextarea"
            v-model="comment"
            placeholder="Provide a brief reason for the edit (required)"
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
            CANCELqw
          </button>
          <button :disabled="loading" class="main-btn main-btn_full-blue" @click="edit">

            <template v-if="loading === true">
              <v-progress-circular indeterminate color="white"/>
            </template>

            <template v-else>
              {{text_edit_button}}
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
  import {mapGetters} from 'vuex'

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
      ...mapGetters(["user"]),

      text_edit_button() {
        return window.innerWidth >= 600 ? 'SUBMIT THE EDIT' : 'SUBMIT'
      },
    },
    methods: {
      edit() {
        var el = $('.main-dialog__textarea');

        if (!this.comment.length) {
          return el.addClass('invalid-input')
        }

        el.removeClass('invalid-input');

        return this.$emit('edit', this.comment);
      }
    },
  }
</script>
