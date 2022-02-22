<template>
    <div class="main-dialog__file-attch-box">
        <div class="main-dialog__file-attch">
            <vue-dropzone :disabled="true"
                          ref="vueDropzone"
                          id="dropzone"
                          class="main-dialog__dropzone"
                          :options="dropzoneOptions"
                          :include-styling="false"
                          :useCustomSlot="true"
                          @vdropzone-removed-file="fileCancel"
                          @vdropzone-file-added="fileAdded"
                          @vdropzone-success="successEvent"
                          @vdropzone-queue-complete="queuecompleteEvent"
                          @vdropzone-sending="sendingEvent"
                          @vdropzone-error="errorFile"
            >

                <template v-if="!hasFiles">
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
        </div>
        <template v-if="hasFiles" >
            <div class="empty-box">
                <button class="main-btn main-btn_border-blue" @click="$refs.vueDropzone.$el.click()">
                    ADD FILES
                </button>
            </div>
        </template>
    </div>
</template>
<script>
    import vue2Dropzone from 'vue2-dropzone'
    import {mapGetters} from 'vuex'
    import {fileAttachments} from '../../plugins/dropzoneTemplates'

    export default {
        components: {
            vueDropzone: vue2Dropzone,
        },
        props: {
            files: Array
        },
        data() {
            return {
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
                    addRemoveLinks: false,
                    previewTemplate: this.getTemplate(),
                    dictDefaultMessage: '',
                    acceptedFiles: 'image/jpeg,image/gif,image/png,.pdf'
                },
            }
        },
        computed: {
            ...mapGetters(["user"]),
        },
        methods: {
            queuecompleteEvent() {
                this.processing = false;
            },
            sendingEvent(file, xhr, formData) {
                this.processing = true;
            },
            fileAdded() {
                this.hasFiles = true
            },
            getTemplate() {
                return fileAttachments()
            },
            fileCancel(file, error, xhr) {
                this.files.map((el, index, arr) => {
                    if (el.name === file.name) {
                        arr.splice(index, 1);
                    }
                });
                if (this.files.length === 0) {
                    this.hasFiles = false
                }
            },
            successEvent(file, response) {

                this.files.push(response.response);
            },
            errorFile(file, message) {
                this.$store.commit('set', {type: 'errorAlert', data: {type: 'error', text: message}})
                this.$refs.vueDropzone.removeFile(file)
                if (this.new_batch.files.length === 0) {
                    this.hasFiles = false
                }
            }
        },
    }
</script>
