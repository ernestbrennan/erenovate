<template>
  <div
    :class="{'attch-history_disabled': readOnly, 'batches_has_changes': has_changes}"
    class="attch-history">
    <div class="file-table">
      <file-table-row
        :file="file"
        :key="file.id"
        v-for="file in batch.files">
      </file-table-row>
    </div>
    <div class="message-attch">
      <div class="message-attch__info-row">
        <img :src="batch.user.photo" alt="img-attch">
        <div class="message-attch__info">
          {{ batch.user.firstname }}, {{ batch.created_at | moment("MMM D h:mm:ss A") }}
        </div>
      </div>
      <div class="message-attch__text">
        {{ batch.description }}
      </div>
    </div>
    <div v-if="!readOnly && !new_batch.files.length" class="attch-history__controls">
      <button class="main-btn main-btn_border-red" @click="removeBatch(batch.id)" v-html="removeText">
      </button>
      <button class="main-btn main-btn_border-blue" @click="editBatch(batch.id)" v-html="editText">
      </button>
    </div>
    <div v-if="has_changes" class="haveChangedFiles-message">Files have been changed</div>

  </div>
</template>

<script>

  import FileTableRow from './Files/FileTableRow.vue'

  export default {
    components: {
      'file-table-row': FileTableRow
    },
    data() {
      return {
        changed_msg: ''
      }
    },
    props: {
      has_changed_files: Boolean,
      new_batch: Object,
      batch: Object,
      readOnly: Boolean,
      batch_old: {
        type: [Object, Boolean],
      },
    },
    computed: {
      has_changes() {
        if (this.batch_old === undefined) {
          return false
        }
        if (this.batch_old === false) {
          this.changed_msg = 'new files'
          return true
        }
        if (this.batch.description !== this.batch_old.description) {
          this.changed_msg = 'new description'
          return true
        }
        if (this.batch.files.length !== this.batch_old.files.length) {
          this.changed_msg = 'new files'
          return true
        }
        for (let i = 0; i < this.batch.files.length; i++) {
          if (this.batch.files[i].name !== this.batch_old.files[i].name) {
            this.changed_msg = 'new files'
            return true
          }
        }
        return false
      },
      removeText() {
        return window.innerWidth > 600 ? 'REMOVE' : `<img src="/img/icon/close_red.svg" alt="Remove">`
      },
      editText() {
        return window.innerWidth > 600 ? 'EDIT' : `<img src="/img/icon/submit-batchet_blue.svg" alt="Edit">`
      },

    },
    methods: {
      removeBatch(batch_id) {
        this.$emit('removeBatch', batch_id)
      },
      editBatch(batch_id) {
        this.$emit('editBatch', batch_id)
      },
    },
    mounted() {
    },
    beforeUpdate() {
    },

  }
</script>

<style>

</style>

