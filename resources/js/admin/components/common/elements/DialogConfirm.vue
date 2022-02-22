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
            </div>
            <div class="main-dialog__footer">

                <div class="main-dialog__footer-btn-row">
                    <template v-if="reverse === 'true'">
                        <button class="main-btn main-btn_full-blue" @click="close">
                            {{content.cancelBtn}}
                        </button>
                        <button class="main-btn main-btn_border-red" @click="checkInputs">
                            {{content.submitBtn}}
                        </button>
                    </template>
                    <template v-else>
                        <button class="main-btn main-btn_border-red" @click="close">
                            {{content.cancelBtn}}
                        </button>
                        <button class="main-btn main-btn_full-blue" @click="checkInputs">
                            {{content.submitBtn}}
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </v-dialog>
</template>

<script>
    export default {
        data() {
            return {}
        },
        props: {
            value: Boolean,
            content: Object,
            reverse: String,
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
            close() {
                this.show = false;
                this.comment = '';
            },
            checkInputs() {
                this.$emit('submit');
                this.show = false;
            },
        },
    }
</script>

<style scoped>

</style>