<template>
    <div>
        <div class="contract-draft__file-attch attch-material"
            :class="{'attch-history_disabled': readOnly, 'attach-has-changes': has_changed_material_files}">

            <template v-if="readOnly">
                <div class="contract-draft__dropzone">
                    <dz-preview
                        v-for="file in material_files"
                        :key="file.id"
                        :file="file"></dz-preview>
                    <div v-if="has_changed_material_files"  class="haveChangedFiles-message">Files have been changed</div>
                </div>
            </template>

            <template v-else>

                <vue-dropzone ref="materialFileDropzone"
                              id="dropzone"
                              class="contract-draft__dropzone"
                              :options="dropzoneOptions"
                              :include-styling="false"
                              :useCustomSlot="true"
                              :destroyDropzone="!readOnly"
                              @vdropzone-removed-file="fileCancel"
                              @vdropzone-success="successEvent"
                              @vdropzone-file-added="fileAdded"
                              @vdropzone-files-added="filesAdded"
                              @vdropzone-queue-complete="queueCompleteEvent"
                              @vdropzone-sending="sendingEvent"
                              @vdropzone-error="errorFile">

                    <template v-if="!hasFiles && !readOnly">
                        <div class="empty-box">
                            <img src="/img/drag-drop_gray.svg" alt="">
                            <p>Drag and Drop Files to Attach <br> or</p>
                            <button class="main-btn main-btn_border-blue">
                                SELECT FILES
                            </button>
                        </div>
                    </template>
                    <template v-else>
                        <h3 class="hidden-message">hidden</h3>
                    </template>

                </vue-dropzone>

                <div class="contract-draft__dropzone-message" v-if="hasFiles && !readOnly">
                    <label class="label-btn__dropzone" @click="$refs.materialFileDropzone.$el.click()">
                        ADD MORE FILES
                    </label>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import vue2Dropzone from 'vue2-dropzone'
    import {materialFileAttachments} from '../../plugins/dropzoneTemplates'
    import dropzone from '../../components/mixins/dropzone/dropzone'
    import DzPreview from './Files/DzPreview.vue'

    export default {
        components: {
            vueDropzone: vue2Dropzone,
            'dz-preview': DzPreview
        },
        mixins: [dropzone],
        data() {
            return {
                counter: 0,
                hasFiles: false,
                processing: false,
                dropzoneOptions: {
                    url: '/api/file.upload',
                    thumbnailWidth: 150,
                    maxFilesize: 10,
                    addRemoveLinks: false,
                    previewTemplate: materialFileAttachments(),
                    dictDefaultMessage: '',
                    acceptedFiles: 'image/jpeg,image/gif,image/png,.pdf'
                },
            }
        },
        props: [
            'material_files',
            'readOnly',
            'has_changed_material_files'
        ],
        methods: {
            errorFile(file, message) {
                this.$store.commit('set', {type: 'errorAlert', data: {type: 'error', text: message}})
                this.$refs.materialFileDropzone.removeFile(file)
            },
            queueCompleteEvent() {
                this.processing = false;
            },
            sendingEvent(file, xhr, formData) {
                this.processing = true;
            },
            fileAdded() {
                this.hasFiles = true
                const timer = setTimeout(() => {
                    this.cropTitle()
                    clearTimeout(timer)
                }, 10)
            },
            filesAdded(){
                this.checkIfEmptyZone()
            },
            checkIfEmptyZone(){
                let current_files =  this.$refs.materialFileDropzone.getAcceptedFiles()
                if ( current_files.length === 0) {
                    this.hasFiles = false
                    return false
                } else {
                    this.hasFiles = true
                    return true
                }
            },

            fileCancel(file, error, xhr) {
                if (!this.readOnly) {
                    this.material_files.map((el, index, arr) => {

                        if (el.name === file.name) {
                            arr.splice(index, 1);
                            return true
                        }
                    });
                    if (this.material_files.length === 0) {
                        this.hasFiles = false
                    }
                }
            },
            successEvent(file, response) {
                this.material_files.push(response.response);
            },
            cropTitle() {
                let files = $('.dz-filename__text');
                for (let i = 0; i < files.length; i++) {
                    if (files[i].innerText.length > 30) {
                        files[i].innerText = files[i].innerText.substring(0, 30) + '...'
                    }
                }
            },
        },
        watch: {
              'readOnly': function(){
                  this.count = 0;
              },
        },
        updated() {
            if (this.$refs.materialFileDropzone && !this.count) {
                this.material_files.map((el) => {
                    this.count++;
                    this.$refs.materialFileDropzone.manuallyAddFile(Object.assign({}, el, {type: el.extension}));
                });
            }
        },
        beforeDestroy() {
            if (this.$refs.materialFileDropzone) {
                this.$refs.materialFileDropzone.removeAllFiles()
            }
        }
    }
</script>
