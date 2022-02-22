<template>
    <v-dialog
        content-class="main-dialog__scroll-content scrollbar"
        v-model="dialogMain.openDialog"
        max-width="556"
        light
    >

        <template v-if="dialog_type === 'with_comment'">
            <dialog-with-comments :contentDialog="dialogMain.contentObj"/>
        </template>
        <template v-else-if="dialog_type === 'cancel'">
            <dialog-cancel
                :contentDialog="dialogMain.contentObj"
            ></dialog-cancel>
        </template>

    </v-dialog>
</template>

<script>
    import DialogWithComment from './Type/DialogWithComment'
    import DialogCancel from './Type/CancelDialog'
    import {mapGetters} from 'vuex'

    export default {
        components: {
            'dialog-with-comments': DialogWithComment,
            'dialog-cancel': DialogCancel,
        },
        props: ['type'],
        computed: {
            ...mapGetters(["dialogMain"]),
            dialog_type() {
                let type = this.type
                if (type === false) {
                    return 'with_comment'
                } else if (type === 'with_comment') {
                    return 'with_comment'
                } else if (type === 'cancel') {
                    return 'cancel'
                }
            },
        },
        watch: {
            dialogMain() {
                console.log(1);
            }
        }
    }
</script>