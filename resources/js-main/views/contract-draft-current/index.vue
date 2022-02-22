<template>
  <div class="contract-comp">
    <controls/>
    <contract-builder :readOnly="readOnly" :contract_draft="contract_draft" :contract="contract"/>
  </div>
</template>

<script>
  import Controls from "./Controls"
  import ContractBuilder from "@/builders/ContractBuilder/ContractBuilder"

  import {mapGetters} from 'vuex'

  import parent_validator from "@/components/mixins/validator/base/parent_validator";

  export default {
    mixins: [parent_validator],
    components: {
      'controls': Controls,
      'contract-builder': ContractBuilder,
    },
    computed: {
      ...mapGetters(["readOnly", "contract_draft", "contract"]),
    },
    created() {
      this.$store.state.readOnly = true;
      this.$store.state.global_loader = true;

      this.$store.dispatch('getCurrentDraft', this.$route.params.guarantee_project_id).then(contract_status => {
        this.$store.state.global_loader = false;

        if (!this.contract_draft) {

          return this.$router.push({
            name: 'contracts',
          });
        }

        if (contract_status !== 'pending') {

          return this.$router.push({
            name: 'contract-signed',
            params: {guarantee_project_id: this.$route.params.guarantee_project_id}
          });
        }
      });
    },
  }
</script>
