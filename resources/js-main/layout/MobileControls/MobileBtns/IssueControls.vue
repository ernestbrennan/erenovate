<template>
  <div>
    <li class="header-controls__chat-option" @click="closeDialog">
      <div class="header-controls__chat-op-innner">
        <img src="/img/icon/close_red.svg" alt="icon-menu">
        <div>Close</div>
      </div>
    </li>
    <li class="header-controls__chat-option" @click="toSummary">
      <div class="header-controls__chat-op-innner">
        <img src="/img/icon/contract_blue.svg" alt="terms">
        <div>Summary</div>
      </div>
    </li>
  </div>
</template>

<script>
  export default {
    name: "IssueControls",
    methods: {
      closeDialog() {
        this.$store.commit('set', {type: 'issueDialogClose', data: true});
      },
      toSummary() {
        return this.$router.push({
          name: 'summary',
          params: {guarantee_project_id: this.$route.params.guarantee_project_id}
        });
      },

    },
  }
</script>
