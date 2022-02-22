<template>
    <div class="contract-comp">
        <div class="row row-mobile">

            <div class="col-xl-9 col-lg-12 col-12 custom-col-mobile">
                <controls :invoice="invoice"/>
                <invoice-builder :readOnly="invoice_read_only" :contentHeight="false" :invoice="invoice"/>
            </div>

            <div class="col-xl-3 hidden-md-and-down">
                <app-timeline :timeline="timeline"></app-timeline>
            </div>

        </div>
    </div>
</template>
<script>
    import {mapGetters} from 'vuex'
    import Timeline from "../../common/Timeline/Timeline";
    import InvoiceBuilder from "../../builders/InvoiceBuilder/InvoiceBuilder";

    import parent_validator from "../../mixins/validator/base/parent_validator"

    import Controls from "./Controls";

    export default {
        mixins: [parent_validator],
        components: {
            'invoice-builder': InvoiceBuilder,
            'app-timeline': Timeline,
            'controls': Controls,
        },
        data() {
            return {}
        },
        computed: {
            ...mapGetters(["invoice_read_only", "timeline", 'invoice']),
        },
        created() {

            this.$store.state.global_loader = true;
            this.$store.dispatch('getInvoiceNew', this.$route.params.guarantee_project_id).then(response => {
                this.$store.commit('set', {type: 'invoice_read_only', data: false});
                this.$store.state.global_loader = false;
            });
        },
    }
</script>