<template>
  <v-dialog light max-width="556" v-model="show">
    <div class="main-dialog main-dialog_open">
      <div class="main-dialog__header">
        <img class="close-dialog" src="/img/icon/close-modal_gray.svg" @click="close">
        <h5 class="main-dialog__header-title">Cancel Payment Request?</h5>
      </div>
      <div class="main-dialog__body">
        <p class="main-dialog__p">
          Are you sure you want to cancel the Milestone {{milestone_sequence}} Payment request?
        </p>
        <p class="main-dialog__p">
          <b>Note:</b> this action cannot be undone, and all payment information will be lost.
        </p>
      </div>
      <div class="main-dialog__footer">
        <div class="main-dialog__footer-btn-row">
          <button class="main-btn main-btn_border-red" @click="close">
            CLOSE
          </button>
          <button class="main-btn main-btn_full-blue" @click="toMessages">
            CANCEL
          </button>
        </div>
      </div>
    </div>
  </v-dialog>
</template>
<script>
  import close_dialog from '@/components/mixins/dialog/close_dialog'

  export default {
    mixins: [close_dialog],
    props: ['milestone_sequence'],
    methods: {

      toMessages() {
        this.close();

        this.$router.push({
          name: 'messages',
          params: {
            guarantee_project_id: this.$route.params.guarantee_project_id
          }
        });
      },
    },
  }
</script>
