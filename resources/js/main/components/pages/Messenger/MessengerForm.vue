<template>
    <div class="chat__footer" data-v-step="ms-10" @dragenter="dragEnterDropzone">

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
                          @vdropzone-total-upload-progress="totalProgressFiles">

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
        <div class="chat__send-form">
            <input class="chat__main-input"
                   data-v-step="ms-11"
                   :placeholder="text_input_placeholder"
                   type="text"
                   v-model="message.content"
                   name="chat_input"
                   v-on:keyup.enter="send"
                   @keydown="isTyping">

            <label for="toDropzone" class="chat__upload-btn step-nt-2" data-v-step="ms-12">
                <input ref="inputDropzone"
                       @click="$refs.messageDropzone.$el.click()"
                       type="submit"
                       id="toDropzone"
                       class="d-none">
                <a data-v-step="nt-4"></a>
                <img :src="icon_file_change" alt="">
            </label>
            
            <v-tooltip top :disabled="!state_send_button">
                <button class="chat__send-btn"
                        :disabled="state_send_button"
                        @click="send"
                        data-v-step="ms-13"
                        slot="activator"
                        v-html="icon_send_button">
                </button>

                <div v-if="state_send_button" class="chat__send-tooltip-empty-msg">
                    {{ text_button_tooltip }}
                </div>
            </v-tooltip>
        </div>
    </div>
</template>
<script>
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'
    import {messengerTemplate} from '../../../plugins/dropzoneTemplates'
    import {mapGetters} from 'vuex'

    export default {
        components: {
            vueDropzone: vue2Dropzone,
        },
        data() {
            return {
                processingFiles: false,
                dragEnter: false,
                dropzoneShow: false, // false
                dragOverForm: false, // false
                windowDropzone: false,
                hasFiles: false,
                message: {
                    files: [],
                    content: ''
                },
                loading_send: false,
                processing: false,
                dropzoneOptions: {
                    url: '/api/file.upload',
                    thumbnailWidth: 150,
                    maxFilesize: 10,
                    addRemoveLinks: false,
                    previewTemplate: messengerTemplate(),
                    dictDefaultMessage: '',
                    acceptedFiles: 'image/jpeg,image/gif,image/png,.pdf'
                }
            }
        },
        props: [
            'type'
        ],
        computed: {
            ...mapGetters(["chat", "user"]),

            text_button_tooltip() {
                if (this.message.files.length) {
                    return 'Please Add Comment to Files First'
                } else if (this.processing === true) {
                    return 'Please wait till files uploading complete'
                } else if (this.loading_send) {
                    return 'Please wait...'
                }
                return 'Please Add Comment'
            },
            icon_file_change() {
                if (this.message.files.length) {
                    return '/img/icon/plus_gray.svg'
                }
                return '/img/icon/chat__upload-btn.svg'
            },
            text_input_placeholder() {
                if (this.message.files.length) {
                    return 'Add a Comment (required)'
                }
                return 'Send Message'
            },
            state_send_button() {
                if (this.message.content.length === 0) {
                    return true
                } else if (this.processing === true) {
                    return true
                } else if (this.loading_send) {
                    return true
                }
                return false
            },
            icon_send_button() {
                const route = this.$route.name;
                if (window.innerWidth >= 768) {
                    return route === 'messages' ? `send` : `add`;
                }
                else {
                    if (this.state_send_button) {
                        return `<img src="/img/icon/send-icon_gray-mobile.svg" alt="send-message">`
                    } else {
                        return `<img src="/img/icon/chat__send-btn.svg" alt="send-message">`
                    }
                }

            },
        },
        methods: {
            sendingEvent(file, xhr, formData) {
                this.processing = true
            },
            cropTitle() {
                let files = $('.dz-filename__text');
                for (let i = 0; i < files.length; i++) {
                    if (files[i].innerText.length > 30) {
                        files[i].innerText = files[i].innerText.substring(0, 30) + '...'
                    }
                }
            },
            queuecompleteEvent() {
                this.processing = false
            },
            send() {
                if (!this.message.content.length || this.processing) {
                    $('chat__main-input').toggleClass('chat__main-input_invalid')
                    return;
                } else if (this.loading_send) {
                    return false
                }
                var params = Object.assign({}, this.message, {
                        guarantee_project_id: this.$route.params.guarantee_project_id
                    }
                );
                this.loading_send = true
                switch (this.type) {
                    case 'message':

                        this.sendMessage(params);
                        break;

                    case 'note':

                        this.sendNotes(params);
                        break;
                }
            },
            sendMessage(params) {
                this.$store.dispatch('sendMessage', params).finally(response => {
                    this.loading_send = false
                    this.clearInput();
                    this.processingFiles = 0;
                })
            },
            sendNotes(params) {

                this.$store.dispatch('sendNote', params).then(response => {
                    this.loading_send = false
                    this.clearInput();
                    this.processingFiles = 0;
                });
            },
            clearInput() {
                let chat = $('.chat__body')
                chat.scrollTop(chat[0].scrollHeight)
                // $('.chat__body').animate({"scrollTop": 1E10}, "slow");

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
                        arr.splice(index, 1);
                        return true
                    }
                });
                this.hasFiles = true
                if (this.message.files.length === 0) {
                    this.hasFiles = false
                }
                this.dropzoneHeight();
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
                const timer = setTimeout(() => {
                    this.cropTitle()
                    this.dropzoneHeight()
                    clearTimeout(timer)
                }, 10);
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
                this.$store.commit('set', {type: 'errorAlert', data: {type: 'error', text: message}})
                this.$refs.messageDropzone.removeFile(file)
            },
            dropFiles() {
                this.dragOverForm = false
                this.dragEnter = false
            },
            totalProgressFiles(totaluploadprogress, totalBytes, totalBytesSent) {
                this.hasFiles = true
                this.processingFiles = totaluploadprogress;
            },
            isTyping() {
                if (this.$route.name === 'messages') {

                    let channel = Echo.join('Chat.Presence.' + this.chat.id);
                    let user_id = this.user.userId;

                    setTimeout(function () {
                        channel.whisper('typing', {
                            user_id: user_id
                        });
                    }, 300);
                }
            }
        },
    }
</script>
