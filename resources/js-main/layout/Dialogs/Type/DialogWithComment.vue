<template>
  <div class="main-dialog main-dialog_open">
    <div class="main-dialog__header">
      <img @click="closeDialog" class="close-dialog"
           src="/img/icon/close-modal_gray.svg">
      <h5 class="main-dialog__header-title">{{cont.title}}</h5>
    </div>

    <div class="main-dialog__body">
      <div class="main-dialog__text-box" v-html="cont.text"></div>
      <div class="main-dialog__form-group">
        <label class="main-dialog__label">{{cont.comment.placeholder}}</label>
        <textarea
          @keyup="resizeTextarea"
          @keydown="resizeTextarea"
          v-model="comment"
          :placeholder="cont.comment.placeholder"
          class="main-dialog__textarea"
          @blur="checkRequiredComment"
        ></textarea>
        <div class="invalid-message">
          This field required
        </div>
      </div>
    </div>

    <div class="main-dialog__footer">
      <div class="main-dialog__footer-btn-row">
        <button class="main-btn main-btn_border-red" @click="closeDialog">
          {{cont.cancelBtn}}
        </button>
        <button class="main-btn main-btn_full-blue" @click="submitDialog">
          {{cont.submitBtn}}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
  import {
    proposeDialog,
    cancelProposeDialog,
    currentDialog
  } from '@/components/dialog/milestoneInProgress'
  import {resizeTextarea} from '@/components/common/Mixins/textarea'

  export default {
    name: "DialogWithComment",
    mixins: [resizeTextarea],
    data() {
      return {
        proposeDialog: proposeDialog,
        cancelProposeDialog: cancelProposeDialog,
        empty: currentDialog,
        comment: '',
      }
    },
    props: {
      contentDialog: [String, Boolean],
    },
    computed: {
      cont() {
        let model = this.contentDialog;
        if (model === false) {
          return this.empty
        } else {
          return this[model]
        }
      },
      commentModel: {
        get() {
          this.$store.state.commentDialog
        },
        set(val) {
          return this.$store.commit('set', {type: 'commentDialog', data: val})
        }
      },
      dialogFun: {
        get() {
          this.$store.state.dialogMain.dialogFun
        },
        set(val) {
          return this.$store.commit('set', {type: 'dialogMain', data: {dialogFun: val}})
        }
      },
    },
    methods: {
      checkRequiredComment() {
        let state = this.comment.length > 0;
        if (!state) {
          $('.main-dialog__textarea').addClass('invalid-input')
        } else {
          this.commentModel = this.comment
          $('.main-dialog__textarea').removeClass('invalid-input')
        }
        return state
      },
      closeDialog() {
        this.$store.commit('set', {
          type: 'dialogMain',
          data: {
            openDialog: false,
            contentObj: false,
            type: false,
            dialogFun: false,
          }
        });
        this.commentModel = '';
      },
      submitDialog() {
        const state = this.checkRequiredComment()
        if (state) {
          this.$store.state.dialogMain.dialogFun()
          this.closeDialog()
        }
      },
    },
  }
</script>
