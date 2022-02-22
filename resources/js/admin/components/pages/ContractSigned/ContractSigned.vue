<template>
    <div class="contract-comp">
        <control-head></control-head>
        <contract-builder :readOnly="readOnly"
                          :contract_draft="contract_draft"
                          :contract="contract"
                          :guarantee_project="guarantee_project"
                          :body_signed="true"
                          :contentHeight="windowHeight"
        ></contract-builder>
    </div>
</template>
<script>
    import ControlHead from "./ControlHead"

    import ContractBuilder from "../../builders/ContractBuilder/ContractBuilder"

    import {mapGetters} from 'vuex'

    export default {
        components: {
            'control-head': ControlHead,
            'contract-builder': ContractBuilder,
        },
        data() {
            return {
                windowHeight: false,
                argumentsToggle: true,
                subjectMatter: true,
                readOnly: true,
            }
        },
        computed: {
            ...mapGetters(["contract_draft", "contract", "guarantee_project"]),
        },
        created() {
            this.$store.state.global_loader = true;
            this.$store.dispatch('getContractSigned', this.$route.params.guarantee_project_id).then(response => {
                this.$store.state.global_loader = false;
                this.$store.dispatch('calculateSummary');
            });
            this.$store.dispatch('getSummary', this.$route.params.guarantee_project_id).then(() => {
                this.$store.state.global_loader = false;
            });

            this.containerHeigth();
            window.addEventListener('resize', this.containerHeigth)
        },
        methods: {
            containerHeigth() {
                let width = window.innerWidth;
                let height = this.$store.state.containerHeight;
                let value = height - 65;
                return false;
                if (width >= 992) {
                    this.windowHeight = value
                    $('.contract-comp').removeAttr("style");
                } else {
                    this.windowHeight = height
                    $('.contract-comp').removeAttr("style");
                }
            },
        },
    }
</script>
<style>

</style>
