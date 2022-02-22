<template>
  <v-dialog light max-width="556" v-model="show">
    <div class="main-dialog main-dialog_open">

      <div class="main-dialog__header">
        <img class="close-dialog" src="/img/icon/close-modal_gray.svg" @click="close">
        <h5 class="main-dialog__header-title">Cancel this Draft?</h5>
      </div>

      <div class="main-dialog__body">
        <p class="main-dialog__p">
          Cancelling this Project Scope Draft means details will be lost. There is no undo.
        </p>
        <p class="main-dialog__p">
          Do you still want to cancel this Draft?
        </p>
      </div>

      <div class="main-dialog__footer">
        <div class="main-dialog__footer-btn-row">
          <button class="main-btn main-btn_border-red" @click="close">
            CLOSE
          </button>
          <button class="main-btn main-btn_full-blue" @click="toProjects">
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
    methods: {

      toProjects() {
        this.$router.push({
          name: 'projects-list',
        })
      },
    },
  }
</script>
