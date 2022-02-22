<template>
  <div>
    <li class="header-controls__chat-option"
        @click="toContractDraft"
    >
      <div class="header-controls__chat-op-innner">
        <img :src="'/img/icon/contract_green.svg'" alt="icon-menu">
        <div>{{contractDraftButton}}</div>
      </div>
    </li>
    <li class="header-controls__chat-option"
        @click="toWithdraw"
    >
      <div class="header-controls__chat-op-innner">
        <img :src="'/img/icon/close_red.svg'" alt="icon-menu">
        <div>Widthdraw Proposal</div>
      </div>
    </li>
    <li class="header-controls__chat-option"
        @click="toFileHistory"
    >
      <div class="header-controls__chat-op-innner">
        <img :src="'/img/icon/files_blue.svg'" alt="icon-menu">
        <div>File History</div>
      </div>
    </li>
  </div>
</template>

<script>
  import {mapGetters} from 'vuex'

  export default {
    data() {
      return {}
    },
    computed: {
      ...mapGetters(["guarantee_project", "contract"]),

      contractDraftButton() {
        if (this.guarantee_project.contract_status === 'signed') {
          return 'Current milestone';
        } else if (this.guarantee_project.contract_status === 'finished' || this.guarantee_project.contract_status === 'completed') {
          return 'Summary';
        } else if (this.guarantee_project.has_contract_draft) {
          return 'Project draft';
        } else {
          return 'Create project draft'
        }
      },
    },
    methods: {
      toWithdraw() {
        this.$store.state.dialogChatWithdraw = true
      },
      toMessages() {
        let targetId = this.guarantee_project.id;
        this.$store.state.slideSearchContracts = false;
        this.$router.push({
          name: 'messages',
          params: {guarantee_project_id: targetId}
        })
      },
      toFileHistory() {
        let targetId = this.guarantee_project.id;
        this.$store.state.slideSearchContracts = false;
        this.$router.push({
          name: 'fileHistory',
          params: {guarantee_project_id: targetId}
        })
      },
      toContractDraft() {
        let targetId = this.$route.params.guarantee_project_id;

        if (this.guarantee_project.contract_status === 'signed') {

          return this.$router.push({
            name: 'current-milestone',
            params: {guarantee_project_id: targetId}
          })
        } else if (this.guarantee_project.contract_status === 'finished' || this.guarantee_project.contract_status === 'completed') {

          return this.$router.push({
            name: 'summary',
            params: {guarantee_project_id: targetId}
          })
        } else if (this.guarantee_project.has_contract_draft) {

          return this.$router.push({
            name: 'current-draft',
            params: {guarantee_project_id: targetId}
          })
        } else {

          return this.$router.push({
            name: 'new-draft',
            params: {guarantee_project_id: targetId}
          });
        }
      },
    }
  }
</script>
