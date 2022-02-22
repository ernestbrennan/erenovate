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
    <template v-if="get_tour">
      <v-tour name="tourCurrInvoice" :steps="steps" :options="optionsVueTour" :callbacks="tourCallbacks"></v-tour>
    </template>
  </div>
</template>
<script>
  import InvoiceBuilder from "@/builders/InvoiceBuilder/InvoiceBuilder";
  import Timeline from "@/components/common/Timeline/Timeline";
  import Controls from "./Controls";

  import parent_validator from "@/components/mixins/validator/base/parent_validator"
  import InvoiceCurListHo from '@/plugins/tour/invoiceCurHo'

  import {mapGetters} from 'vuex'

  export default {
    mixins: [parent_validator, InvoiceCurListHo],
    components: {
      'invoice-builder': InvoiceBuilder,
      'app-timeline': Timeline,
      'controls': Controls,
    },
    data() {
      return {
        guarantee_project_id: this.$route.params.guarantee_project_id
      }
    },
    computed: {
      ...mapGetters(["readOnly", "timeline", 'invoice', 'user']),
      get_tour() {
        return window.innerWidth >= 1200
      },
    },
    created() {
      this.$store.state.global_loader = true;
      this.$store.dispatch('getCurrentInvoice', this.guarantee_project_id).then(milestone => {

        if (!this.invoice) {

          return this.$router.push({
            name: 'list-invoice',
            params: {
              guarantee_project_id: this.guarantee_project_id
            }
          })
        }

        this.$store.state.global_loader = false;
        this.$store.commit('updateInvoiceSummary', 'total_price');
      })
    },
    beforeDestroy() {
      this.$store.state.invoiceCurrent.dialogComplete = false
      this.$store.state.invoiceCurrent.dialogReject = false
    }
  }
</script>
