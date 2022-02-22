<template>
    <div class="chat__footer"
         data-v-step="ms-10"
         @dragenter="dragEnterDropzone">

        <div v-show="this.dropzoneShow"
             ref="dropzoneBox"
             class="chat__dropzone"
             id="chat_dropzone-id"
             :class="{ active: this.dropzoneShow, 'has-files': this.hasFiles,'drag-enter': this.dragEnter}"
             :style="{height: windowDropzone}">

            <vue-dropzone ref="messageDropzone"
                          id="dropzoneChatMain"
                          :options="dropzoneOptions"
                          :include-styling="false"
                          :useCustomSlot="true"
                          @vdropzone-file-added="fileAdded"
                          @vdropzone-removed-file="fileCancel"
                          @vdropzone-drag-enter="dropzoneDragEnter"
                          @vdropzone-files-added="filesAdded"
                          @vdropzone-drop="dropFiles"
                          @vdropzone-error="drError"
                          @vdropzone-success="successEvent"
                          @vdropzone-queue-complete="queuecompleteEvent"
                          @vdropzone-sending="sendingEvent"
                          @vdropzone-total-upload-progress="totalProgressFiles"

            >
                <template v-if="dragOverForm">
                    <div class="chat-dropzone__drag-from-for-wraper">
                        <div class="chat-dropzone__drag-from-form">
                            <div>
                                <img src="/img/icon/dropzone-chatdrag_blue.svg" alt="chatdrag"> Drop Files To Start
                                Upload
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <h3 class="d-none">hidden</h3>
                </template>
            </vue-dropzone>
        </div>
        <div class="chat__send-form" >
            <template v-if="showEnterForm">
                <input class="chat__main-input"
                       data-v-step="ms-11"
                       :placeholder="inputPlaceholder"
                       type="text"
                       v-model="message.content"
                       name="chat_input"
                       v-on:keyup.enter="send"
                       :disabled="closed"
                >

                <label for="toDropzone" class="chat__upload-btn step-nt-2" data-v-step="ms-12">
                    <input ref="inputDropzone"
                           @click="$refs.messageDropzone.$el.click()"
                           type="submit"
                           id="toDropzone"
                           class="d-none"
                           v-if="!closed"
                    >
                    <a data-v-step="nt-4"></a>
                    <img :src="changeIconFile" alt="">
                </label>
                <v-tooltip top
                           :disabled="!stateSendBtn"
                >
                    <button class="chat__send-btn"
                            :disabled="stateSendBtn"
                            @click="send"
                            data-v-step="ms-13"
                            slot="activator"
                            v-html="sendIcon"
                    >
                    </button>

                    <div v-if="stateSendBtn" class="chat__send-tooltip-empty-msg">
                        {{ tooltipComment }}
                    </div>
                </v-tooltip>
            </template>
        </div>
    </div>
</template>
<script>
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'
    import {messengerTemplate} from '../../../plugins/dropzoneTemplates'


    export default {
        components: {
            vueDropzone: vue2Dropzone,
        },

        data() {
            return {
                processingFiles:false,
                dragEnter: false,
                dropzoneShow: false, // false
                dragOverForm: false, // false
                windowDropzone: false,
                hasFiles: false,
                showEnterForm: true,
                message: {
                    files: [],
                    content: ''
                },
                processing: false,
                dropzoneOptions: {
                    url: '/api/file.upload',
                    thumbnailWidth: 150,
                    maxFilesize: 10,
                    addRemoveLinks: false,
                    previewTemplate: this.getTemplate(),
                    dictDefaultMessage: '',
                    acceptedFiles: 'image/jpeg,image/gif,image/png,.pdf'
                }
            }
        },
        props: [
            'issue'
        ],
        computed: {
            closed(){
                return this.issue && this.issue.status === 'closed'
            },
            tooltipComment(){
                if (this.closed) {
                    return 'Issue is closed'
                }
                if(this.message.files.length){
                    return 'Please Add Comment to Files First';
                } else if (this.processing === true){
                    return 'Please wait till files uploading complete';
                }
                return 'Please Add Comment'
            },
            changeIconFile() {
                if (this.message.files.length) {
                    return '/img/icon/plus_gray.svg'
                }
                return '/img/icon/chat__upload-btn.svg'
            },
            inputPlaceholder() {
                if (this.message.files.length) {
                    return 'Comment to file (required)'
                }
                return 'Send Message'
            },
            stateSendBtn() {
                if (this.message.content.length === 0) {
                    return true
                } else if(this.processing === true){
                    return true
                }
                return false
            },
            sendIcon() {
                if (window.innerWidth >= 768) {
                    return `send`
                }
                else {
                    if(this.stateSendBtn){
                        return `<img src="/img/icon/send-icon_gray-mobile.svg" alt="send-message">`
                    } else {
                        return `<img src="/img/icon/chat__send-btn.svg" alt="send-message">`
                    }
                }

            },
        },
        methods: {
            getTemplate() {return messengerTemplate();},
            sendingEvent(file, xhr, formData) {
                this.processing = true
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
                this.processing = false
            },
            send() {
                if (!this.message.content.length || this.processing) {
                    $('chat__main-input').toggleClass('chat__main-input_invalid')
                    return;
                }
                var params = Object.assign({}, this.message, {
                        issue_id: this.$route.params.issue_id
                    }
                );
                this.$store.dispatch('sendIssueMessage', params).then(response => {

                    this.clearInput();
                    this.processingFiles = 0;
                });
            },
            clearInput() {
                $('.chat__body').animate({"scrollTop": 1E10}, "slow");

                if (this.$refs.messageDropzone !== undefined) {

                    this.$refs.messageDropzone.removeAllFiles(true);
                }

                this.message = {
                    files: [],
                    content: ''
                }
            },

            fileCancel(file, error, xhr) {
                this.message.files.map((el, index, arr) => {
                    if (el.uuid === file.upload.uuid) {
                        console.log(arr);
                        arr.splice(index, 1);
                        return true
                    }
                });
                this.hasFiles = true
                if(this.message.files.length === 0 ){
                    this.hasFiles = false
                }
                this.dropzoneHeight();
            },
            clearAllFiles() {
                this.$refs.messageDropzone.removeAllFiles(true)
                this.hasFiles = false
                this.message.files = []
            },

            successEvent(file, response) {
                this.message.files.push(Object.assign({}, response.response, {uuid: file.upload.uuid}));
            },
            dropzoneHeight() {
                setTimeout(() => {
                    let dropzone = this.$refs.dropzoneBox
                    dropzone.removeAttribute('style');
                    let chatHeight = this.$store.state.chatBodyHeight,
                        cHeight = dropzone.offsetHeight;
                    if (cHeight >= chatHeight) {
                        this.$refs.dropzoneBox.style.height = chatHeight + 'px'
                        dropzone.classList.add('scrollbar')
                        dropzone.style.overflowY = 'scroll'
                    } else {
                        this.windowDropzone = cHeight + 'px'
                        dropzone.classList.remove('scrollbar')
                        dropzone.style.overflowY = 'initial'
                    }
                }, 1)
            },
            fileAdded(file) {
                this.hasFiles = true
                const timer = setTimeout(()=>{
                    this.cropTitle()
                    this.dropzoneHeight()
                    clearTimeout(timer)
                },10);
            },
            filesAdded() {
                this.hasFiles = true
            },
            dragEnterDropzone() {
                this.dropzoneShow = true
                this.dragOverForm = true
            },
            dropzoneDragEnter() {
                this.dragEnter = true
                if (this.message.files.length) {
                    this.$refs.dropzoneBox.style.height = 140 + 'px'
                }
            },
            drError(file, message) {
                this.hasFiles = true
                this.$store.commit('set',{type: 'errorAlert', data:{type:'error', text: message}})
                this.$refs.messageDropzone.removeFile(file)
            },
            dropFiles() {
                this.dragOverForm = false
                this.dragEnter = false
            },
            totalProgressFiles(totaluploadprogress, totalBytes, totalBytesSent){
                this.hasFiles = true
                this.processingFiles = totaluploadprogress;
            },

        },
    }
</script>
<style>

</style>

