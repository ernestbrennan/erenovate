<template>
    <div>
        <div class="contract-draft__file-attch attch-material"
             :class="{'attch-history_disabled': readOnly}"
        >
            <template v-if="readOnly">
            <div class="contract-draft__dropzone">
                <div :key="file.id" v-for="file in material_files" class="dz-preview dz-processing dz-image-preview dz-success dz-complete">
                    <div class="dz-inner-box">
                        <div class="dz-info">
                            <div class="dz-filename">
                                <div class="dz-progressbar__box">
                                    <img src="/img/icon/one-file_gray.svg" alt="file">
                                </div>
                                <span class="dz-filename__text">{{file__name(file.name)}}</span>
                            </div>
                            <div class="dz-size">
                                <span v-html="formatSize(file.size)"></span>
                            </div>
                        </div>
                        <div class="dz-option-box">
                            <img @click="download(file.id)"  class="dz-upload-target" src="/img/icon/upload_gray.svg"
                                 alt="Click me to download">
                        </div>
                    </div>
                </div>
            </div>
            </template>
            <template v-else-if="!readOnly">
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
                              @vdropzone-queue-complete="queuecompleteEvent"
                              @vdropzone-sending="sendingEvent"
                              @vdropzone-error="errorFile"
                >

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

    export default {
        components: {
            vueDropzone: vue2Dropzone,
        },
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
                    previewTemplate: this.getTemplate(),
                    dictDefaultMessage: '',
                    acceptedFiles: 'image/jpeg,image/gif,image/png,.pdf'
                },
            }
        },
        props: [
            'material_files',
            'readOnly'
        ],
        filters: {
        },
        methods: {
            download(file_id) {
                api({
                    method: 'post',
                    url: '/api/file.download',
                    data: {
                        file_id: file_id
                    },
                    responseType: 'blob'
                })
                    .then(response => {

                        let a = document.createElement('a');
                        let url = window.URL.createObjectURL(response.data);

                        a.href = url;
                        a.download = decodeURIComponent(response.headers['file-name']);
                        a.click();

                        window.URL.revokeObjectURL(url);
                    });

            },
            formatSize(bytes) {

                if (bytes >= 1073741824) {

                    bytes = "<strong>" +(bytes / 1073741824).toFixed(2) + "</strong> GB";

                } else if (bytes >= 1048576) {

                    bytes = "<strong>" +(bytes / 1048576).toFixed(2) + "</strong> MB";

                } else if (bytes >= 1024) {

                    bytes = "<strong>"+(bytes / 1024).toFixed(2) + "</strong> KB";
                }

                return bytes;
            },
            errorFile(file, message) {
                this.$store.commit('set', {type: 'errorAlert', data: {type: 'error', text: message}})
                this.$refs.materialFileDropzone.removeFile(file)
            },
            queuecompleteEvent() {
                this.processing = false;
            },
            sendingEvent(file, xhr, formData) {
                this.processing = true;
            },
            fileAdded() {
                this.hasFiles = true
                const timer = setTimeout(()=>{
                    this.cropTitle()
                    clearTimeout(timer)
                },10)
            },
            getTemplate() {

                return materialFileAttachments()
            },
            fileCancel(file, error, xhr) {

                this.material_files.map((el, index, arr) => {

                    if (el.name === file.name) {
                        arr.splice(index, 1);
                        return true
                    }
                });
                if (this.material_files.length === 0) {
                    this.hasFiles = false
                }
            },
            successEvent(file, response) {

                this.material_files.push(response.response);
            },
            cropTitle(){
                let files = $('.dz-filename__text');
                for (let i = 0 ; i < files.length; i++){
                    if(files[i].innerText.length > 30){
                        files[i].innerText = files[i].innerText.substring(0, 30) + '...'
                    }
                };
            },
            file__name(name){
                if (name.length >= 35){
                    return name.substring(0, 35) + '...'
                }
                return name;
            },
        },
        mounted() {
        },
        updated(){
            if(!this.readOnly){
                if(this.counter === 0){
                    this.material_files.map((el) => {
                        this.counter++
                        this.$refs.materialFileDropzone.manuallyAddFile(Object.assign({}, el, {type: el.extension}));
                    });
                }
            }
        },
        beforeDestroy() {
            // if(!this.readOnly){
            //     this.$refs.materialFileDropzone.removeAllFiles()
            // }
        }
    }
</script>
<style>

</style>