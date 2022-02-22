<template>
  <div>
    <li class="header-controls__chat-option">
      <div class="header-controls__chat-op-innner"
           @click="toCurrentDraft"
      >
        <img src="/img/icon/contract_blue.svg" alt="terms">
        <div>Current Version</div>
      </div>
    </li>
    <li class="header-controls__chat-option">
      <div class="header-controls__chat-op-innner"
           @click="toHistory"
      >
        <img src="/img/icon/history_blue.svg" alt="history">
        <div>Drafts History</div>
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
      ...mapGetters(["guarantee_project"]),
    },
    methods: {
      toHistory() {
        this.$store.state.slideSearchContracts = false;
        this.$router.push({
          name: 'contract-history',
          params: {
            guarantee_project_id: this.guarantee_project.id
          }
        })
      },
      toCurrentDraft() {
        this.$store.state.slideSearchContracts = false;
        this.$router.push({
          name: 'current-draft',
          params: {guarantee_project_id: this.guarantee_project.id}
        })
      },
    },

  }
</script>
