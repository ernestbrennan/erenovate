<template>
  <div class="contract-comp">
    <controls/>
    <contract-builder :readOnly="true" :show_user_info="false" :contract_draft="contract_draft" :contract="contract"/>
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
      ...mapGetters(["contract_draft", "contract"]),
    },
    created() {
      var params = {
        guarantee_project_id: this.$route.params.guarantee_project_id,
        draft_version: this.$route.params.draft_version,
      };

      this.$store.state.global_loader = true;
      this.$store.dispatch('getHistoryDraft', params).then(() => {
        this.$store.state.global_loader = false;
      });
    },
  }
</script>
