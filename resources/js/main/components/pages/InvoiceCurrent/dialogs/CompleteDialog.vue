<template>
    <v-dialog light max-width="556" v-model="show" content-class="main-dialog__scroll-content scrollbar">
        <div class="main-dialog main-dialog_open">

            <div class="main-dialog__header">
                <img class="close-dialog" src="/img/icon/close-modal_gray.svg" @click="close">
                <h5 class="main-dialog__header-title">Confirm Payment</h5>
            </div>

            <div class="main-dialog__body">
                <p class="main-dialog__p">
                    The client has accepted your Payment Request for this Milestone, and
                    they confirm sending you the payment.
                </p>
                <p class="main-dialog__p">
                    To confirm you received full payment for this Milestone,
                    and payment details submitted by client on eRenovate are accurate,
                    type CONFIRM and click <i>Verify Payment</i> button.
                </p>
                <p class="main-dialog__p">
                    Type CONFIRM below to verify payment received.
                </p>
                <div class="main-dialog__form-group">
                    <label class="main-dialog__label">I received Milestone Payment? (required)</label>
                    <textarea placeholder="Provide brief description of payment transaction (required)"
                              class="main-dialog__textarea"
                              name="Comment reject payment"
                              @keyup="resizeTextarea"
                              @keydown="resizeTextarea"
                              v-model="comment">
                    </textarea>
                    <div class="invalid-message">This field required</div>
                </div>
            </div>

            <div class="main-dialog__footer">
                <div class="main-dialog__footer-btn-row">
                    <button class="main-btn main-btn_border-red" @click="close">
                        CANCEL
                    </button>
                    <button :disabled="loading" class="main-btn main-btn_full-blue" @click="complete">
                        <template v-if="loading === true">
                            <v-progress-circular indeterminate color="white"/>
                        </template>
                        <template v-else>
                            VERIFY PAYMENT
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
        props: ['loading'],
        data() {
            return {
                comment: ''
            }
        },
        methods: {
            complete() {
                var comment = $('.main-dialog__textarea');

                comment.removeClass('invalid-input');

                if (this.comment.length) {
                    return this.$emit('complete', this.comment);
                }

                comment.addClass('invalid-input');
            }
        }
    }
</script>