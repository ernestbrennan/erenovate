<template>
  <div class="main-dialog main-dialog_open">
    <div class="main-dialog__header">
      <img @click="closeDialog" class="close-dialog"
           src="/img/icon/close-modal_gray.svg">
      <h5 class="main-dialog__header-title">{{cont.title}}</h5>
    </div>
    <div class="main-dialog__body">
      <div class="main-dialog__text-box" v-html="cont.text"></div>
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
  import {cancelProposeDialog} from '@/components/dialog/milestoneInProgress'

  export default {
    data() {
      return {
        cancelProposeDialog: cancelProposeDialog,
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
        this.$store.state.commentDialog = '';
      },
      submitDialog() {
        this.$store.state.dialogMain.dialogFun()
        this.closeDialog()
      },

    },
  }
</script>

<style scoped>

</style>
