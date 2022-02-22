<template>
  <div class="contract-comp">
    <template v-if="desktop_only">
      <div class="row row-mobile">
        <div class="col-xl-9 col-lg-12 col-12 custom-col-mobile">
          <div class="contract-comp__header hidden-sm-and-down">
            <div class="g-flex g-flex_row g-flex_wrap margin-10">
              <div data-v-step="ilho-1" class="contract-header__title hidden-sm-and-down">
                Payment request
              </div>
              <v-spacer class="hidden-sm-and-down"></v-spacer>
              <button v-if="has_current_invoice" class="main-btn main-btn_full-blue" @click="toCurrentInvoice">
                CURRENT PAYMENT REQUEST
              </button>
            </div>
          </div>
          <div
            class="contract-comp__body component-body_scroll scrollbar pa-0"
            :style="{height: component_container_height}"
          >
            <div class="contract-invoice__list">
              <invoice data-v-step="ilho-3" :key="invoice.id" v-for="invoice in invoices" :invoice="invoice"/>
            </div>
          </div>
        </div>
        <div class="col-xl-3 hidden-md-and-down">
          <contract-timeline :timeline="timeline"/>
        </div>
      </div>
    </template>
    <template v-else>
      <div class="contract-comp__header hidden-sm-and-down">
        <div class="g-flex g-flex_row g-flex_wrap margin-10">
          <div class="contract-header__title hidden-sm-and-down">
            Payment request
          </div>
          <v-spacer class="hidden-sm-and-down"></v-spacer>
          <button v-if="has_current_invoice" class="main-btn main-btn_full-blue" @click="toCurrentInvoice">
            CURRENT PAYMENT REQUEST
          </button>
        </div>
      </div>
      <div :style="{height: component_container_height}" class="contract-comp__body component-body_scroll scrollbar">
        <div class="contract-invoice__list">

          <invoice
            data-v-step="ilho-3" v-for="invoice in invoices"
            :key="invoice.id"
            :invoice="invoice"
          />
        </div>
      </div>
    </template>
    <template v-if="get_tour">
      <v-tour name="tourInvoiceList" :steps="steps" :options="optionsVueTour" :callbacks="tourCallbacks"></v-tour>
    </template>
  </div>

</template>

<script>
  import ContractTimeline from '@/components/common/Timeline/Timeline'
  import Invoice from './Invoice'
  import {mapGetters} from 'vuex'
  import inListTour from '@/plugins/tour/invoiceList'

  export default {
    mixins: [inListTour],
    components: {
      'contract-timeline': ContractTimeline,
      'invoice': Invoice
    },
    data() {
      return {
        has_current_invoice: false,
      }
    },
    computed: {
      ...mapGetters(["invoices", "timeline", "user"]),

      desktop_only() {
        return window.innerWidth >= 992
      },

      get_tour() {
        return window.innerWidth >= 1200
      },
      component_container_height() {
        let width = window.innerWidth;
        let height = this.$store.state.containerHeight;
        let value = height - 65;
        if (width >= 992) {
          return value + 'px'
        } else {
          return height + 'px'
        }
      }
    },
    methods: {
      checkCurrentInvoice() {
        const state = this.invoices.some((el) => {
          return el.status === 'pending'
        });
        this.has_current_invoice = state;
        return state
      },
      toCurrentInvoice() {
        this.$router.push({
          name: 'current-invoice',
          params: {
            guarantee_project_id: this.$route.params.guarantee_project_id
          }
        })
      },
    },
    created() {
      this.$store.state.global_loader = true;
      this.$store.dispatch('getInvoices', this.$route.params.guarantee_project_id).then(response => {

        this.$store.state.global_loader = false
        this.checkCurrentInvoice();
      });
    },
  }
</script>
