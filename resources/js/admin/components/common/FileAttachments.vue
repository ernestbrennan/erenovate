<template>
    <div class="contract-draft__file-attch-box">
        <div data-v-step="ccd-9"></div>
        <div class="contract-draft__file-attch-history" data-v-step="ccd-10">
            <div :key="batch.id"
                 v-for="batch in batches"
                 :class="{'attch-history_disabled': readOnly}"
                 class="attch-history" >
                <div class="file-table">
                    <div :key="file.id" v-for="file in batch.files" class="file-table__row">
                        <div class="file-table__info">
                            <div class="file-table__title">
                                <div class="file-table__title-img"><img :src="'/img/icon/chat-file.svg'" alt=""></div>
                                <div class="file-table__title-text">{{ file.name| cropName }}</div>
                            </div>
                            <div class="file-table__size">{{ file.size|formatSize }}</div>
                        </div>
                        <div class="file-table__link">
                            <a @click.prevent="download(file.id)" v-html="downloadInner"></a>
                        </div>
                    </div>
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
            </div>
        </div>
        <template v-if="!readOnly">
            <div data-v-step="ccd-10" :class="{'attch-history_disabled': readOnly}"
                 class="contract-draft__file-attch">
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

                <template v-if="hasFiles">
                    <div class="contract-draft__dropzone-message">
                    <textarea class="contract-draft__textarea"
                              placeholder="Comment to file (required)"
                              ref="commentFile"
                              @blur="validateTextarea"
                              @input="resizeTextarea"
                              v-model="new_batch.description">
                    </textarea>
                        <div class="dropzone-message__btn-row">
                            <button class="main-btn main-btn_border-red"
                                    @click="clearNewBatch">
                                CANCEL
                            </button>
                            <button class="main-btn main-btn_border-blue"
                                    @click="postNewBatch">
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

    export default {
        mixins: [editResizeTextarea, resizeTextarea],
        components: {
            vueDropzone: vue2Dropzone,
        },
        props: {
            batches: Array,
            readOnly: Boolean,
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
        filters: {
            cropName: function(string_name){
                if(string_name.length> 30){
                    return string_name.substring(0, 30) + '...'
                }
                return string_name
            },
            formatSize: function (bytes) {

                if (bytes >= 1073741824) {

                    bytes = (bytes / 1073741824).toFixed(2) + " GB";

                } else if (bytes >= 1048576) {

                    bytes = (bytes / 1048576).toFixed(2) + " MB";

                } else if (bytes >= 1024) {

                    bytes = (bytes / 1024).toFixed(2) + " KB";
                }

                return bytes;
            }
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
        },
        methods: {
            validateTextarea(event){
                if(this.new_batch.description.length > 0){
                    event.target.classList.remove('invalid-input');
                } else {
                    event.target.classList.add('invalid-input');
                }
            },
            filesAdded() {
                this.hasFiles = true;
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
            fileAdded(file) {
                this.hasFiles = true;
            },
            getTemplate() {
                return fileAttachments()
            },
            fileCancel(file, error, xhr) {

                this.new_batch.files.map((el, index, arr) => {
                    if (el.name === file.name) {
                        arr.splice(index, 1);
                    }
                });
                if (this.new_batch.files.length === 0) {
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
            },
            postNewBatch() {
                console.log(typeof batches);
                if (this.new_batch.description.length === 0) {
                    this.$refs.commentFile.classList.add('invalid-input');
                    return false
                }
                if (this.processing) {
                    this.$store.commit('set', {type: 'errorAlert', data: {type: 'error', text: 'Files uploading'}})
                    return false
                }

                let new_batch = this.new_batch;
                let randomId = parseInt(Math.floor(Math.random() * 999));
                new_batch.created_at = new Date();
                new_batch.user = this.user;
                new_batch.id = randomId;

                this.batches.push(JSON.parse(JSON.stringify(new_batch)));
                this.edited_batch = null;

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
                            this.$refs.vueDropzone.manuallyAddFile(Object.assign(file, {type: ''}));
                        });

                        arr.splice(index, 1);
                    }
                });
               const timer = setTimeout(()=>{
                 this.textareaEditResize(this.edited_batch.description,'commentFile');
                 this.cropTitle();
                 clearTimeout(timer);
               }, 200)
            },
            successEvent(file, response) {

                this.new_batch.files.push(response.response);
            },
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
                        a.download = response.headers['file-name'];
                        a.click();

                        window.URL.revokeObjectURL(url);
                    });

            },
            errorFile(file, message) {
                this.$store.commit('set', {type: 'errorAlert', data: {type: 'error', text: message}})
                this.$refs.vueDropzone.removeFile(file)
            }
        },
        watch: {
            'new_batch.files': function () {
            }
        },
        mounted() {
        }
    }
</script>
<style>

</style>
