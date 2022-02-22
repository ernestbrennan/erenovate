<template>
  <div class="contract-comp">
    <controls/>
    <contract-builder :readOnly="false" :contract_draft="contract_draft" :contract="contract"/>
  </div>
</template>

<script>
  import Controls from "./Controls"
  import ContractBuilder from "@/builders/ContractBuilder/ContractBuilder"

  import {ParentMixin} from "@/components/common/Mixins/glValidate/mixins";
  import {mapGetters} from 'vuex'
  import {getByIdArchivedDraft} from "@/api/archived-draft";
  import {getInfoUser} from "@/api/user";

  export default {
    mixins: [ParentMixin],
    components: {
      'controls': Controls,
      'contract-builder': ContractBuilder,
    },
    created() {

      this.$store.dispatch('clearContract');
      this.$store.dispatch('clearContractDraft');

      if (this.$route.params.archived_draft_id) {
        this.getArchivedDraft()
      } else {
        this.getUserInfo()
      }
    },

    computed: {
      ...mapGetters(["contract_draft", "contract", "guarantee_project"]),
    },

    methods: {
      getUserInfo() {
        this.$store.state.global_loader = true;

        getInfoUser().then(response => {
          this.$store.state.global_loader = false;
          this.$store.state.contract.current_user_info = response.data.response;
        });
      },
      getArchivedDraft() {
        this.$store.state.global_loader = true;

        getByIdArchivedDraft(this.$route.params.archived_draft_id).then(response => {
          let draft = response.data.response.draft;

          this.$store.commit('set', {type: 'contract', data: draft.contract});
          this.$store.commit('set', {type: 'contract_draft', data: draft.contract_draft});

          this.$store.state.global_loader = false;

        }).catch(err => {

          return this.$router.push({name: 'invite-ho'});
        });
      }
    }
  }
</script>
