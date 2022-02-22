<template>
    <v-dialog light max-width="556" v-model="show" content-class="main-dialog__scroll-content scrollbar">
        <div class="main-dialog main-dialog_open">

            <div class="main-dialog__header">
                <img @click="close" class="close-dialog"
                     src="/img/icon/close-modal_gray.svg">
                <h5 class="main-dialog__header-title">
                    {{content.title}}
                </h5>
            </div>

            <div class="main-dialog__body">
                <div class="main-dialog__text-box" v-html="content.text"></div>
                <div class="main-dialog__form-group">
                    <label class="main-dialog__label">{{content.comment.label}}</label>
                    <textarea
                            @focus="clearInput('textareaDesc')"
                            ref="textareaDesc"
                            v-model="comment"
                            :placeholder="content.comment.placeholder"
                            class="main-dialog__textarea"
                            @input="checkRequiredComment('comment','textareaDesc')"
                    ></textarea>
                    <div class="invalid-message">
                        This field required
                    </div>
                </div>
            </div>

            <div class="main-dialog__footer">
                <div class="main-dialog__footer-btn-row">
                    <button class="main-btn main-btn_border-red" @click="close">
                        {{content.cancelBtn}}
                    </button>
                    <button class="main-btn main-btn_full-blue" @click="checkInputs">
                        {{content.submitBtn}}
                    </button>
                </div>
            </div>
        </div>
    </v-dialog>
</template>

<script>
    import {nextTickResize} from "../../common/Mixins/textarea";

    export default {
        name: "DialogWithComments",
        mixins: [nextTickResize],
        data() {
            return {
                comment: '',
            }
        },
        props: {
            value: Boolean,
            content: Object,
        },
        computed: {
            show: {
                get() {
                    return this.value
                },
                set(value) {
                    this.$emit('input', value)
                }
            },
        },
        methods: {
            clearInput(ref_id) {
                let ref = this.$refs[ref_id];
                $(ref).removeClass('invalid-input');
            },
            checkRequiredComment(value, ref_id) {
                let state = this[value].length > 0;
                let ref = this.$refs[ref_id];
                if (!state) {
                    $(ref).addClass('invalid-input');
                } else {
                    $(ref).removeClass('invalid-input');
                }
                return state
            },
            close() {
                this.show = false;
                this.comment = '';
            },
            checkInputs() {
                const comment = this.checkRequiredComment('comment', 'textareaDesc');
                if (comment) {
                    this.emit('submit', this.comment);
                } else {
                    console.log('fail validation')
                    return false
                }
            },
        },
    }
</script>

<style scoped>

</style>