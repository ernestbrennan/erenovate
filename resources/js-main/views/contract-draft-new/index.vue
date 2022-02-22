<template>
  <div class="contract-comp">
    <controls/>
    <contract-builder :readOnly="false" :contract_draft="contract_draft" :contract="contract"/>
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
    created() {
      this.$store.state.global_loader = true;

      this.$store.dispatch('clearContractDraft');

      this.$store.dispatch('getContract', this.$route.params.guarantee_project_id).then(() => {

        this.contract_draft.milestones[0].milestone_content.title = this.guarantee_project.project_name;
        this.contract_draft.milestones[0].milestone_content.description = this.guarantee_project.project_description;

        this.$store.state.global_loader = false;
      });
    },

    computed: {
      ...mapGetters(["contract_draft", "contract", "guarantee_project"]),
    },

    beforeRouteLeave(to, from, next) {
      this.$store.commit('set', {type: 'routerRedirect', data: to});
      if (this.$store.state.contractNewDraftDialog.CancelCreation === true ||
        this.$store.state.contractNewDraftDialog.ContractCreate === true ||
        this.$store.state.tourStart === true) {
        this.$store.state.contractNewDraftDialog.CancelCreation = false;
        this.$store.state.contractNewDraftDialog.ContractCreate = false;
        this.$store.commit('set', {'type': 'routerRedirect', data: false});
        next()
      } else {
        next(false);
        this.$store.state.contractNewDraftDialog.CancelCreation = true;
      }
    },
  }
</script>
