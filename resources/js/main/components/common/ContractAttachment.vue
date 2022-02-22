<template>
    <div id="signed_dropzone" class="contract-draft__file-attch-box">
        <div class="contract-draft__file-attch-history">
            <div :class="{'attch-history_disabled': readOnly}" class="attch-history" v-if="contract_signed.file">
                <div class="file-table" >
                    <file-table-row
                        :file="contract_signed.file"
                    ></file-table-row>
                </div>
                <div class="message-attch">

                    <div class="message-attch__text">
                        {{ contract_signed.description }}
                    </div>
                </div>
                <div v-if="!readOnly" class="attch-history__controls">
                    <button class="main-btn main-btn_border-red" @click="clearContractSigned" v-html="removeText">
                    </button>
                </div>
            </div>
        </div>
        <template v-if="!readOnly && !contract_signed.file">
            <div data-v-step="ccd-10" :class="{'attch-history_disabled': readOnly}"
                 class="contract-draft__file-attch">
                <vue-dropzone
                        :disabled="true"
                        ref="contractSignedDropzone"
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
                            <img src="/img/drag-drop_gray.svg" alt="">
                            <p> Drag & Drop Signed Contract Scan to attach</p>
                            <button class="main-btn main-btn_border-blue">
                                SELECT FILES
                            </button>
                        </div>
                    </template>

                    <template v-else>
                        <h3 class="hidden-message">hidden</h3>
                    </template>
                </vue-dropzone>

                <template v-if="hasFiles">
                    <div class="contract-draft__dropzone-message">
                    <textarea class="contract-draft__textarea contract-draft__textarea_mb-30"
                              placeholder="Comment to file (required)"
                              ref="commentFile"
                              @input="resizeTextarea"
                              v-model="contract_signed.description">
                    </textarea>
                        <div class="dropzone-message__btn-row">
                            <button class="main-btn main-btn_border-red"
                                    @click="clearContractSigned">
                                CANCEL
                            </button>
                            <button class="main-btn main-btn_border-blue"
                                    @click="postContractSigned">
                                {{postAttach}}
                            </button>
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
    import dropzone from '../mixins/dropzone/dropzone';
    import FileTableRow from './Files/FileTableRow.vue'

    export default {
        mixins: [editResizeTextarea, resizeTextarea, dropzone],
        components: {
            vueDropzone: vue2Dropzone,
            'file-table-row': FileTableRow,
        },
        props: {
            id_dropzone: {
                type: String,
                default: 'dropZone_1230',
            },
            contract_draft: Object,
            readOnly: Boolean,
        },
        data() {
            return {
                hasFiles: false,
                processing: false,
                new_file: null,
                dropzoneOptions: {
                    url: '/api/file.upload',
                    thumbnailWidth: 150,
                    maxFilesize: 10,
                    maxFiles: 1,
                    addRemoveLinks: false,
                    previewTemplate: this.getTemplate(),
                    dictDefaultMessage: '',
                    acceptedFiles: 'image/jpeg,image/gif,image/png,.pdf'
                },
            }
        },
        filters: {
            cropName: function(string_name){
                if(string_name.length> 30){
                    return string_name.substring(0, 30) + '...'
                }
                return string_name
            },
        },
        computed: {
            ...mapGetters(["user"]),
            downloadInner() {
                return (window.innerWidth >= 768) ? 'DOWNLOAD' : `<img src="/img/icon/download-mobile-chat_blue.svg" alt="DOWNLOAD">`
            },
            postAttach() {
                return (window.innerWidth >= 768) ? 'UPLOAD ATTACHMENT' : 'UPLOAD';
            },
            removeText(){
                return window.innerWidth > 600 ? 'REMOVE' : `<img src="/img/icon/close_red.svg" alt="Remove">`
            },
            editText(){
                return window.innerWidth > 600 ? 'EDIT' : `<img src="/img/icon/submit-batchet_blue.svg" alt="Edit">`
            },

            contract_signed : {
                get(){
                    return this.contract_draft.contract_signed
                },
                set(contract_signed) {
                    this.contract_draft.contract_signed = contract_signed
                }
            }
        },
        methods: {
            getTemplate() {return fileAttachments()},
            filesAdded() {
                this.hasFiles = true;
                this.$store.commit('setContractDropzone', {type: 'hasFiles', data:true })
                const timer = setTimeout(()=>{
                    this.cropTitle()
                    clearTimeout(timer)
                },10)
            },
            cropTitle(){
                let files = $('.dz-filename__text');
                for (let i = 0 ; i < files.length; i++){
                    if(files[i].innerText.length > 30){
                        files[i].innerText = files[i].innerText.substring(0, 30) + '...'
                    }
                };
            },
            queuecompleteEvent() {
                this.processing = false;
            },
            sendingEvent(file, xhr, formData) {
                this.processing = true;
            },
            checkIfEmptyZone(){
                let current_files =  this.$refs.contractSignedDropzone.getAcceptedFiles()
                if ( current_files.length === 0) {
                    this.hasFiles = false
                    this.$store.commit('setContractDropzone', {type: 'hasFiles', data:false });
                    return false
                } else {
                    this.hasFiles = true
                    return true
                    this.$store.commit('setContractDropzone', {type: 'hasFiles', data:true });
                }
            },
            fileAdded(file) {
                this.hasFiles = true
                this.$store.commit('setContractDropzone', {type: 'hasFiles', data:true });
            },
            filesAdded(file) {
                this.checkIfEmptyZone()
            },
            fileCancel(file, error, xhr) {
                this.hasFiles = false;
                this.$store.commit('setContractDropzone', {type: 'hasFiles', data:false });
            },
            clearContractSigned() {

                if (this.$refs.contractSignedDropzone) {
                    this.$refs.contractSignedDropzone.removeAllFiles(true);
                    this.$store.commit('setContractDropzone', {type: 'hasFiles', data:false });
                }

                this.contract_signed = {
                    description: '',
                    file: null
                };
                this.$store.commit('setContractDropzone', {type: 'valid', data:false });

            },
            postContractSigned() {
                if (this.processing) {
                    this.$store.commit('set', {type: 'errorAlert', data: {type: 'error', text: 'File is uploading'}})
                    return false
                }
                this.$store.commit('setContractDropzone', {type: 'valid', data:true })
                this.$store.commit('setContractDropzone', {type: 'hasFiles', data:true })
                this.contract_signed.file  = this.new_file
                this.contract_draft.contract_signed = this.contract_signed;

            },

            editContractSigned(){
                this.contract_signed.file = null
                this.hasFiles = true;
                this.$store.commit('setContractDropzone', {type: 'hasFiles', data:true })

            },

            successEvent(file, response) {
                this.new_file = response.response;
            },

            errorFile(file, message) {
                this.$store.commit('set', {type: 'errorAlert', data: {type: 'error', text: message}})
                this.$refs.contractSignedDropzone.removeFile(file)
                this.checkIfEmptyZone()
            }
        },
        created() {
            this.contract_signed = this.contract_draft.contract_signed;
        },
    }
</script>
