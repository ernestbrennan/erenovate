<template>
  <div class="summary-block">
    <h3 class="summary-block__border-title sum-tour-5">Project Payments</h3>
    <div class="milestone-table">
      <div class="milestone-table__row hidden-sm-and-down">
        <div class="milestone-table__work milestone-table__row-el">
          <h5 class="milestone-table__title sum-tour-6">Description of Payments</h5>
        </div>
        <div class="milestone-table__est-price milestone-table__row-el">
          <h5 class="milestone-table__title sum-tour-7">Estimated Price</h5>
        </div>
        <div class="milestone-table__fin-price milestone-table__row-el">
          <h5 class="milestone-table__title sum-tour-8">Final Price</h5>
        </div>
      </div>

      <div :key="index" v-for="(payment, index) in summary_table.payments">
        <payment :payment="payment"/>
      </div>

      <div class="milestone-table__row milestone-table__pr-row">
        <template v-if="summary_table.payment_total.final_material > 0">
          <div class="milestone-table__work milestone-table__row-el">
            <div class="milestone-table__pr-title sum-tour-9">Services Price</div>
            <div class="milestone-table__price">$ {{summary_table.payment_total.final_work}}</div>
          </div>
          <div class="milestone-table__est-price milestone-table__row-el">
            <div class="milestone-table__pr-title sum-tour-10">Material price</div>
            <div class="milestone-table__price">$ {{summary_table.payment_total.final_material}}</div>
          </div>
        </template>
        <div class="milestone-table__fin-price milestone-table__row-el">
          <div class="milestone-table__pr-title sum-tour-11">Total price</div>
          <div class="milestone-table__price">$ {{summary_table.payment_total.final_total}}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Payment from './Payment'
  import {mapGetters} from "vuex";

  export default {
    components: {
      'payment': Payment,
    },
    computed: {
      ...mapGetters({
        summary_table: 'summary/summary_table'
      })
    }
  }
</script>
