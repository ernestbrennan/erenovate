<template>
    <div class="contract-comp__body component-body_scroll scrollbar contract-comp_summary"
         :style="{height: componetConteinerHeight}">

        <contract-timeline :timeline="summary.history"/>

        <payment-list :payments="summary.summary_table.payments" :payment_total="summary.summary_table.payment_total"/>

        <completing-contract v-if="needConfirmComplete"/>

        <warranty-protection v-if="summary.contract.status === 'completed'" :issues="summary.issues"/>

        <project-completeness v-if="summary.contract.status === 'finished' && (summary.contract.ho_confirm_complete || user.role === 'contractor')" />

    </div>

</template>
<script>
    import Timeline from './ContractTimeline/Timeline'
    import PaymentList from './Payments/PaymentList'
    import ContractComp from './CompletingContract/CompletingContract'
    import WarrantyProtection from './WarrantyProtection/WarrantyProtection'
    import ProjectCompleteness from './ProjectCompleteness/ProjectCompleteness'

    import {mapGetters} from 'vuex'
    import {ContainerHeight} from '../../common/Mixins/builder'

    export default {
        mixins: [ContainerHeight],
        components: {
            'contract-timeline': Timeline,
            'payment-list': PaymentList,
            'completing-contract': ContractComp,
            'warranty-protection': WarrantyProtection,
            'project-completeness':ProjectCompleteness
        },
        props: {
            contentHeight: [Boolean, Number],
            read_only: Boolean,
        },
        computed: {
            needConfirmComplete(){
                return this.summary.contract.status === 'finished' &&
                    !this.summary.contract.ho_confirm_complete &&
                    this.user.role === 'homeowner'
            },
            ...mapGetters(["user", "summary"])
        }
    }
</script>
