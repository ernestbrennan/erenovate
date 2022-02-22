<template>
    <v-dialog light max-width="556" v-model="show" content-class="main-dialog__scroll-content">

        <div class="main-dialog main-dialog_open">

            <div class="main-dialog__header">
                <img class="close-dialog" src="/img/icon/close-modal_gray.svg" @click="close">
                <h5 class="main-dialog__header-title">Milestone Completed…</h5>
            </div>

            <div class="main-dialog__body">
                <div class="main-dialog__text-box">
                    <p class="main-dialog__p">
                        This Milestone is now completed and ‘closed’, you will now be directed to create a Milestone Payment Request.
                    </p>
                    <p class="main-dialog__p">
                        Once your Payment Request is submitted, client is notified and required to upload proof of payment to eRenovate. Once proof of payment is uploaded, client will ask you to confirm receiving payment.
                    </p>
                    <p class="main-dialog__p">
                        Once you confirm the payment, the Milestone status will automatically change to Completed.
                    </p>
                </div>
            </div>

            <div class="main-dialog__footer">
                <div class="main-dialog__footer-btn-row">
                    <button class="main-btn main-btn_full-blue" @click="toCreateInvoice">
                        CREATE PAYMENT REQUEST
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
        methods: {
            toCreateInvoice() {
                this.$router.push({
                    name: 'create-invoice',
                    params: {
                        guarantee_project_id: this.$route.params.guarantee_project_id,
                    }
                })
            },
        }
    }
</script>