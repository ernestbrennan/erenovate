<template>
    <v-dialog light max-width="556" v-model="show" content-class="main-dialog__scroll-content scrollbar">
        <div class="main-dialog main-dialog_open">

            <div class="main-dialog__header">
                <img @click="close" class="close-dialog"
                     src="/img/icon/close-modal_gray.svg">
                <h5 class="main-dialog__header-title">
                    Claim an issue
                </h5>
            </div>

            <div class="main-dialog__body">
                <p class="main-dialog__p">
                    Sorry, now we are working on this functionality
                </p>
                <div class="main-dialog__form-group">
                    <label class="main-dialog__label">Support email</label>
                    <div class="main-dialog__copy-input-box">
                        <input type="text"
                               v-model="mail"
                               id="mailSupport"
                               ref="mail"
                               disabled
                               class="main-dialog__input-text"
                        >
                        <label @click="copy()" class="main-dialog__copy-label"></label>
                    </div>
                </div>
            </div>

            <div class="main-dialog__footer">
                <div class="main-dialog__footer-btn-row">
                    <button ref="done" class="main-btn main-btn_full-blue" @click="close">
                        DONE
                    </button>
                </div>
            </div>
        </div>
    </v-dialog>
</template>

<script>
    export default {
        data() {
            return {
                mail: 'memberservices@erenovate.com',
            }
        },
        props: {
            value: Boolean,
        },
        computed: {
            reportText(){
                return window.innerWidth >= 600 ? 'REPORT AN ISSUE' : 'REPORT'
            },
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
            copy(){
                const el = this.$refs.mail;
                el.removeAttribute('disabled');
                el.select();
                document.execCommand("copy");
                el.setAttribute('disabled', 'disabled');
                this.$refs.done.focus();
            },
            close() {
                this.show = false;
            },
        },
    }
</script>

<style scoped>

</style>