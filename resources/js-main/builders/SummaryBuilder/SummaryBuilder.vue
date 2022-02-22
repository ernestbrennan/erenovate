<template>
  <div
    class="contract-comp__body component-body_scroll scrollbar contract-comp_summary"
    :style="{height: componetConteinerHeight}"
  >

    <contract-timeline/>
    <payment-list/>

    <completing-contract v-if="needConfirmComplete"/>

    <warranty-protection v-if="summary_contract.status === 'completed'"/>

    <project-completeness v-if="needProjectCompleteness"/>

  </div>
</template>
<script>
  import Timeline from './ContractTimeline/Timeline'
  import PaymentList from './Payments/PaymentList'
  import ContractComp from './CompletingContract/CompletingContract'
  import WarrantyProtection from './WarrantyProtection/WarrantyProtection'
  import ProjectCompleteness from './ProjectCompleteness/ProjectCompleteness'

  import {mapGetters} from 'vuex'
  import {ContainerHeight} from '@/components/common/Mixins/builder'

  export default {
    mixins: [ContainerHeight],
    components: {
      'contract-timeline': Timeline,
      'payment-list': PaymentList,
      'completing-contract': ContractComp,
      'warranty-protection': WarrantyProtection,
      'project-completeness': ProjectCompleteness
    },
    props: {
      contentHeight: [Boolean, Number],
      read_only: Boolean,
    },
    computed: {
      needConfirmComplete() {
        return this.summary_contract.status === 'finished' &&
          !this.summary_contract.ho_confirm_complete &&
          this.user.role === 'homeowner'
      },
      needProjectCompleteness(){
        return this.summary_contract.status === 'finished' &&
          (this.summary_contract.ho_confirm_complete || this.user.role === 'contractor')
      },
      ...mapGetters({
        user: 'user',
        summary_contract: 'summary/contract'
      })
    }
  }
</script>
