<template>
  <div class="contract-draft__file-attch-box">
    <div data-v-step="ccd-9"></div>
    <div class="contract-draft__file-attch-history" data-v-step="ccd-10">
      <batch
        :key="batch.id"
        :readOnly="readOnly"
        :batch="batch"
        :has_changed_files="has_changed_files"
        :new_batch="new_batch"
        :batch_old="checkButchOld(index)"
        v-for="(batch, index) in batches"
        @removeBatch="removeBatch"
        @editBatch="editBatch"
      />
    </div>
    <template v-if="!readOnly">
      <div
        data-v-step="ccd-10"
        :class="{'attch-history_disabled': readOnly, 'hasOpenBatch': $store.state.hasNewBatch}"
        class="contract-draft__file-attch"
      >
        <vue-dropzone
          :disabled="true"
          ref="vueDropzone"
          id="dropzone"
          class="contract-draft__dropzone"
          :options="dropzoneOptions"
          :include-styling="false"
          :useCustomSlot="true"
          @vdropzone-removed-file="fileCancel"
          @vdropzone-file-added="fileAdded"
          @vdropzone-files-added="filesAdded"
          @vdropzone-success="successEvent"
          @vdropzone-queue-complete="queuecompleteEvent"
          @vdropzone-sending="sendingEvent"
          @vdropzone-error="errorFile"
        >

          <template v-if="!hasFiles">
            <div class="empty-box">
              <img src="/img/drag-drop_gray.svg">
              <p v-if="$route.name === 'create-invoice'">Drag and Drop related files to send with Payment Request</p>
              <p v-else>Drag and Drop Files to Attach <br> or</p>
              <button class="main-btn main-btn_border-blue">SELECT FILES</button>
            </div>
          </template>

          <template v-else>
            <h3 class="hidden-message">hidden</h3>
          </template>
        </vue-dropzone>

        <template v-if="hasFiles">
          <div class="contract-draft__dropzone-message contract-draft__batch-not-submited">
            <textarea
              class="contract-draft__textarea contract-draft__textarea_mb-30"
              placeholder="Comment to file (required)"
              ref="commentFile"
              @blur="validateTextarea"
              @input="resizeTextarea"
              v-model="new_batch.description"
            >
            </textarea>
            <div class="dropzone-message__btn-row">
              <button class="main-btn main-btn_border-red" @click="clearNewBatch">CANCEL</button>
              <button class="main-btn main-btn_border-blue" @click="postNewBatch">{{postAttach}}</button>
            </div>
          </div>
        </template>
      </div>
    </template>
  </div>
</template>
<script>
  import vue2Dropzone from 'vue2-dropzone'
  import {mapGetters} from 'vuex'
  import {fileAttachments} from '../../plugins/dropzoneTemplates'
  import {editResizeTextarea, resizeTextarea} from '../common/Mixins/textarea'
  import dropzone from '../../components/mixins/dropzone/dropzone'
  import FileTableRow from './Files/FileTableRow.vue'
  import Batch from './Batch.vue'

  export default {
    mixins: [editResizeTextarea, resizeTextarea, dropzone],
    components: {
      vueDropzone: vue2Dropzone,
      'file-table-row': FileTableRow,
      'batch': Batch
    },
    props: {
      id_dropzone: {
        type: String,
        default: 'dropZone_0',
      },
      batches: Array,
      batches_old_version: {
        type: [Array, Boolean],
        default: function () {
          return []
        }
      },
      readOnly: Boolean,
      has_changed_files: {
        type: Boolean,
        default: false
      },
    },
    data() {
      return {
        edited_batch: null,
        new_batch: {
          description: '',
          files: [],
        },
        hasFiles: false,
        processing: false,
        dropzoneOptions: {
          url: '/api/file.upload',
          thumbnailWidth: 150,
          maxFilesize: 10,
          maxFiles: 5,
          addRemoveLinks: false,
          previewTemplate: this.getTemplate(),
          dictDefaultMessage: '',
          acceptedFiles: 'image/jpeg,image/gif,image/png,.pdf'
        },
      }
    },
    computed: {
      ...mapGetters(["user"]),
      changed_description() {
        if (this.batches_old_version.length !== this.batches.length) {
          return 'Files have been changed. Old number of file blocks: ' + this.batches_old_version.length
        }
        return 'Files have been changed'
      },
      downloadInner() {
        return (window.innerWidth >= 768) ? 'DOWNLOAD' : `<img src="/img/icon/download-mobile-chat_blue.svg" alt="DOWNLOAD">`
      },
      postAttach() {
        return (window.innerWidth >= 768) ? 'UPLOAD ATTACHMENT' : 'UPLOAD';
      },
      removeText() {
        return window.innerWidth > 600 ? 'REMOVE' : `<img src="/img/icon/close_red.svg" alt="Remove">`
      },
      editText() {
        return window.innerWidth > 600 ? 'EDIT' : `<img src="/img/icon/submit-batchet_blue.svg" alt="Edit">`
      },
    },
    methods: {
      checkButchOld(index) {
        if (this.has_changed_files) {
          return this.batches_old_version[index] === undefined ? false : this.batches_old_version[index]
        } else {
          return undefined
        }
      },
      validateTextarea(event) {
        if (this.new_batch.description.length > 0) {
          event.target.classList.remove('invalid-input');
        } else {
          event.target.classList.add('invalid-input');
        }
      },
      filesAdded() {
        this.checkIfEmptyZone()
        //this.hasFiles = true;
        this.$store.commit('set', {type: 'hasNewBatch', data: true})
        this.$store.commit('setValidDropZone', {id: this.id_dropzone, invalid: true})
        const timer = setTimeout(() => {
          this.cropTitle()
          clearTimeout(timer)
        }, 10)
        this.checkIfEmptyZone()

      },
      cropTitle() {
        let files = $('.dz-filename__text');
        for (let i = 0; i < files.length; i++) {
          if (files[i].innerText.length > 30) {
            files[i].innerText = files[i].innerText.substring(0, 30) + '...'
          }
        }
        ;
      },
      queuecompleteEvent() {
        this.processing = false;
      },
      sendingEvent(file, xhr, formData) {
        this.processing = true;
      },
      fileAdded(file) {
        this.hasFiles = true;
        this.$store.commit('set', {type: 'hasNewBatch', data: true})
        this.$store.commit('setValidDropZone', {id: this.id_dropzone, invalid: true})
      },
      getTemplate() {
        return fileAttachments()
      },
      checkIfEmptyZone() {
        let current_files = this.$refs.vueDropzone.getAcceptedFiles()
        if (current_files.length === 0) {
          this.$store.commit('set', {type: 'hasNewBatch', data: false})
          this.$store.commit('setValidDropZone', {id: this.id_dropzone, invalid: false})
          this.hasFiles = false
          return false
        } else {
          this.$store.commit('set', {type: 'hasNewBatch', data: false})
          this.$store.commit('setValidDropZone', {id: this.id_dropzone, invalid: false})
          this.hasFiles = true
          return true
        }
      },
      fileCancel(file, error, xhr) {
        this.new_batch.files.map((el, index, arr) => {
          if (el.name === file.name) {
            arr.splice(index, 1);
          }
        });
        if (this.new_batch.files.length === 0) {
          this.$store.commit('set', {type: 'hasNewBatch', data: false})
          this.$store.commit('setValidDropZone', {id: this.id_dropzone, invalid: false})
          this.hasFiles = false
        }
      },
      clearNewBatch() {

        if (this.edited_batch) {
          this.batches.push(JSON.parse(JSON.stringify(this.edited_batch)));
          this.edited_batch = null;
        }

        this.$refs.vueDropzone.removeAllFiles(true);

        this.new_batch = {
          description: '',
          files: []
        };
        this.$store.commit('set', {type: 'hasNewBatch', data: false})
        this.$store.commit('setValidDropZone', {id: this.id_dropzone, invalid: false})
      },
      postNewBatch() {
        if (this.new_batch.description.length === 0) {
          this.$refs.commentFile.classList.add('invalid-input');
          return false
        }
        if (this.processing) {
          this.$store.commit('set', {type: 'errorAlert', data: {type: 'error', text: 'File is uploading'}})
          return false
        }

        let new_batch = this.new_batch;
        let randomId = parseInt(Math.floor(Math.random() * 127));

        new_batch.created_at = new Date();
        new_batch.user = this.user;
        new_batch.id = randomId;

        this.batches.push(JSON.parse(JSON.stringify(new_batch)));
        this.edited_batch = null;
        this.$store.commit('set', {type: 'hasNewBatch', data: false})
        this.$store.commit('setValidDropZone', {id: this.id_dropzone, invalid: true})

        this.clearNewBatch();
      },
      removeBatch(batch_id) {
        this.batches.map((el, index, arr) => {
          if (el.id === batch_id) {
            arr.splice(index, 1);
          }
        });
      },
      editBatch(batch_id) {
        this.batches.map((el, index, arr) => {

          if (el.id === batch_id) {
            this.new_batch = el;
            this.edited_batch = JSON.parse(JSON.stringify(el));

            el.files.map((file) => {
              this.$refs.vueDropzone.manuallyAddFile(Object.assign(file, {type: 'image/png'}));
            });

            arr.splice(index, 1);
          }
        });
        const timer = setTimeout(() => {
          this.textareaEditResize(this.edited_batch.description, 'commentFile');
          this.cropTitle();
          clearTimeout(timer);
        }, 200)
        this.$store.commit('set', {type: 'hasNewBatch', data: true})
        this.$store.commit('setValidDropZone', {id: this.id_dropzone, invalid: true})
      },
      successEvent(file, response) {

        this.new_batch.files.push(response.response);
      },
      errorFile(file, message) {
        this.$store.commit('set', {type: 'errorAlert', data: {type: 'error', text: message}})
        this.$refs.vueDropzone.removeFile(file)
      }
    },
    mounted() {
      this.$store.commit('mountedValidDropZone', {id: this.id_dropzone, invalid: false})
    },
    beforeDestroy() {
      this.$store.commit('removeValidDropZone', this.id_dropzone)
    }
  }
</script>
