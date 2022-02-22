<template>
  <v-dialog light max-width="556" v-model="show">
    <div class="main-dialog main-dialog_open">

      <div class="main-dialog__header">
        <img class="close-dialog" src="/img/icon/close-modal_gray.svg" @click="close">
        <h5 class="main-dialog__header-title">Delete this Project Scope Draft?</h5>
      </div>

      <div class="main-dialog__body">
        <p class="main-dialog__p">
          Deleting this Project Scope Draft means details will be lost. There is no undo.
        </p>
      </div>

      <div class="main-dialog__footer">
        <div class="main-dialog__footer-btn-row">
          <button class="main-btn main-btn_border-red" @click="close">
            CLOSE
          </button>
          <button class="main-btn main-btn_full-blue" @click="$emit('delete')">
            Delete
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
  }
</script>
