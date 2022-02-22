<template>
    <v-dialog light max-width="556" v-model="show" content-class="scrollbar">
        <div class="main-dialog main-dialog_open">

            <div class="main-dialog__header">
                <img @click="close" class="close-dialog" src="/img/icon/close-modal_gray.svg">
                <h5 class="main-dialog__header-title">Propose Project Scope</h5>
            </div>

            <div class="main-dialog__body">
                <p class="main-dialog__p">
                    You are sending the Project Scope to the other party
                    for review. We’ll notify you once they Accept or Edit
                    the Project Scope details.
                </p>
                <p class="main-dialog__p">
                    By proposing this Project Scope, you agree to the details of the Project.
                </p>
                <div class="main-dialog__form-group">
                    <label class="main-dialog__label">Comment</label>
                    <textarea @keyup="resizeTextarea"
                              @keydown="resizeTextarea"
                              v-model="comment"
                              placeholder="Provide a brief comment to send with draft (required)"
                              class="main-dialog__textarea">
                    </textarea>
                    <div class="invalid-message">
                        This field required
                    </div>
                </div>
            </div>
<!--            alter table tbl_project modify lead_from enum('eRenovateGuarantee', 'NF', 'web', 'app', 'mitten', 'amerispec', 'amana', 'elandscape', 'elandscapeapp', 'Watercloset', 'edecorate', 'edecorateapp', 'myhomepage', 'HanstoneIPAD') null;-->
            <div class="main-dialog__footer">
                <div class="main-dialog__footer-btn-row">
                    <button class="main-btn main-btn_border-red" @click="close">
                        CANCEL
                    </button>
                    <button :disabled="loading" class="main-btn main-btn_full-blue" @click="create" >

                        <template v-if="loading === true">
                            <v-progress-circular indeterminate color="white" />
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
    import {resizeTextarea} from '../../../common/Mixins/textarea'
    import close_dialog from '../../../mixins/dialog/close_dialog'

    export default {
        mixins: [resizeTextarea, close_dialog],
        data(){
            return {
                comment: ''
            }
        },
        props: {
            loading: Boolean
        },
        computed: {
            text_create_propose_button() {
                return window.innerWidth >= 768 ? 'PROPOSE DRAFT' : 'OK'
            },
        },
        methods: {
            create(){
                var el = $('.main-dialog__textarea');

                if (!this.comment.length) {
                    return el.addClass('invalid-input')
                }

                el.removeClass('invalid-input');

                return this.$emit('create', this.comment);
            }
        },
    }
</script>
