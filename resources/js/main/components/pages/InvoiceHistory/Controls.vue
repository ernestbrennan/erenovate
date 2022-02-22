<template>
    <div class="contract-comp__header">
        <div class="g-flex g-flex_row g-flex_wrap margin-10 g-flex_vertical-center">
            <div class="contract-header__title hidden-sm-and-down">
                Payment request for Milestone {{invoice.milestone.sequence}}
                <span>Signed  {{invoice.created_at}}</span>
            </div>
            <div class="contract-header__contract-label">
                {{text_status}}
            </div>
            <v-spacer class="hidden-md-and-down"></v-spacer>
            <v-spacer class="hidden-md-and-up"></v-spacer>
            <button class="main-btn main-btn_border-blue-deep" @click="exportInvoice">
                <template v-if="download_pdf_loading === true">
                    <v-progress-circular indeterminate color="#1875F0" />
                </template>
                <template v-else>
                    Download PDF
                </template>
            </button>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import export_invoice from '../../mixins/export/invoice'

    export default {
        mixins: [export_invoice],
        props: {
            invoice: Object,
            milestone_id: Number
        },
        computed: {
            text_status() {
                let status = this.invoice.status;
                if (status === 'rejected') return 'Declined';
                if (status === 'completed') return 'Completed';
            },
            ...mapGetters(["user", "guarantee_project"]),
        },
    }
</script>
