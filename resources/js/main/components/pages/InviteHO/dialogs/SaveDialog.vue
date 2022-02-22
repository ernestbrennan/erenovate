<template>
    <v-dialog light max-width="556" v-model="show" content-class="scrollbar">
        <div class="main-dialog main-dialog_open">

            <div class="main-dialog__header">
                <img class="close-dialog" src="/img/icon/close-modal_gray.svg" @click="close">
                <h5 class="main-dialog__header-title">
                    Save Project Scope Draft
                </h5>
            </div>

            <div class="main-dialog__body">
                <p class="main-dialog__p">
                    You are about to save Project Scope Draft without submitting it to the client. It will be available on the "Drafts" page. You can get back to editing it later.
                </p>

                <div class="main-dialog__form-group">
                    <label class="main-dialog__label">Draft Title</label>
                    <textarea @keyup="resizeTextarea"
                              @keydown="resizeTextarea"
                              v-model="title"
                              placeholder="Provide a title for your draft(required)"
                              class="main-dialog__textarea">
                    </textarea>
                    <div class="invalid-message">
                        This field required
                    </div>
                </div>
            </div>

            <div class="main-dialog__footer">
                <div class="main-dialog__footer-btn-row">
                    <button class="main-btn main-btn_border-red" @click="close">
                        CANCEL
                    </button>
                    <button class="main-btn main-btn_full-blue" @click="create" :disabled="loading">
                        <template v-if="loading">
                            <v-progress-circular indeterminate color="white"/>
                        </template>
                        <template v-else>
                            {{text_create_propose_button}}
                        </template>
                    </button>
                </div>
            </div>

        </div>
    </v-dialog>
</template>
<script>
    import close_dialog from '../../../mixins/dialog/close_dialog'
    import {resizeTextarea} from '../../../common/Mixins/textarea'

    export default {
        mixins: [close_dialog, resizeTextarea],
        computed: {
            text_create_propose_button() {
                return window.innerWidth >= 768 ? 'SAVE A DRAFT' : 'SAVE'
            },
        },
        props: ['loading'],
        data() {
            return {
                title: ''
            }
        },
        methods: {
            create(){
                var el = $('.main-dialog__textarea');

                if (!this.title.length) {
                    return el.addClass('invalid-input')
                }

                el.removeClass('invalid-input');

                return this.$emit('save', this.title);
            }
        },
    }
</script>
