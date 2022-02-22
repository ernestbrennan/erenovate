<template>
    <div class="contract-comp">
        <template v-if="desktopOnly">
            <div class="row row-mobile">
                <div class="col-xl-9 col-lg-12 col-12">
                    <controls />
                    <contract-builder :contract_draft="contract_draft"
                                      :contentHeight="windowHeight"
                                      :readOnly="readOnly"
                                      :contract="contract"
                                      :body_signed="true"/>
                </div>
                <div class="col-xl-3 hidden-md-and-down">
                    <contract-timeline :timeline="timeline" />
                </div>
            </div>
        </template>
        <template v-else>
            <controls />
            <contract-builder :contract_draft="contract_draft"
                              :contentHeight="windowHeight"
                              :readOnly="readOnly"
                              :contract="contract"
                              :body_signed="true" />
        </template>
    </div>
</template>
<script>
    import Controls from "./Controls"
    import ContractTimeline from "../../common/Timeline/Timeline"
    import ContractBuilder from "../../builders/ContractBuilder/ContractBuilder"

    import parent_validator from "../../mixins/validator/base/parent_validator";

    import {mapGetters} from 'vuex'

    export default {
        mixins:[parent_validator],
        components: {
            'controls': Controls,
            'contract-builder': ContractBuilder,
            'contract-timeline': ContractTimeline,
        },
        data() {
            return {
                windowHeight: false,
            }
        },
        computed: {
            ...mapGetters(["contract_draft", "contract",  "readOnly", "timeline"]),
            desktopOnly(){ return window.innerWidth >= 992 },
        },
        created() {
            this.$store.commit('set', {type: 'readOnly', data: true});

            this.$store.state.global_loader = true;
            this.$store.dispatch('getContractSigned', this.$route.params.guarantee_project_id).then(response => {
                this.$store.state.global_loader = false;
                this.$store.dispatch('calculateSummary');
            });

        },
    }
</script>
