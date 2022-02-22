<template>
    <div class="contract-comp">
        <div class="row row-mobile">
            <div class="col-xl-9 col-lg-12 col-12 custom-col-mobile">

                <controls :invoice="invoice"/>
                <invoice-builder :readOnly="true" :contentHeight="false" :invoice="invoice"/>

            </div>
            <div class="col-xl-3 hidden-md-and-down">
                <app-timeline :timeline="timeline"/>
            </div>
        </div>
    </div>
</template>
<script>
    import {mapGetters} from 'vuex'

    import InvoiceBuilder from "../../builders/InvoiceBuilder/InvoiceBuilder";
    import Timeline from "../../common/Timeline/Timeline";
    import Controls from "./Controls";

    import parent_validator from "../../mixins/validator/base/parent_validator"

    export default {
        mixins: [parent_validator],
        components: {
            'invoice-builder': InvoiceBuilder,
            'app-timeline': Timeline,
            'controls': Controls,
        },
        data() {
            return {
            }
        },
        computed: {
            ...mapGetters(["readOnly", "timeline", 'invoice']),
        },
        created() {
            this.$store.state.global_loader = true;

            this.$store.dispatch('getHistoryInvoice', this.$route.params.invoice_id)
                .then(response => {

                    this.$store.state.global_loader = false;
                    this.$store.commit('updateInvoiceSummary', 'total_price');
                });
        },
    }
</script>
