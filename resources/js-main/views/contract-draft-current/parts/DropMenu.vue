<template>
  <v-menu class="hidden-md-and-down" v-show="mobile_and_tablet_state" offset-y left nudge-top="-16">

    <button class="main-btn contract-header__dropmenu-box main-btn_border-blue" slot="activator">
      <span class="dropmenu-box__dots"></span>
      MORE
    </button>
    <div class="contract-header__drop-target">
      <ul class="option-list-dropdown">
        <li class="option-list-dropdown__el" @click="$emit('proposeTerms')">
          <img src="/img/icon/terms_blue.svg" alt="terms">
          <span>Propose Different Terms</span>
        </li>
        <li class="option-list-dropdown__el" @click="toDraftHistory">
          <img src="/img/icon/history_blue.svg" alt="history">
          <span>Drafts History</span>
        </li>
        <li class="option-list-dropdown__el" @click="toMessages">
          <img src="/img/icon/messages_blue.svg" alt="messages">
          <span>Project Messages</span>
        </li>
      </ul>
    </div>
  </v-menu>
</template>
<script>
  export default {
    data() {
      return {
        guarantee_project_id: this.$route.params.guarantee_project_id
      }
    },
    computed: {
      mobile_and_tablet_state() {
        return window.innerWidth >= 992
      },
    },
    methods: {
      toMessages() {
        this.$router.push({
          name: 'messages',
          params: {
            guarantee_project_id: this.guarantee_project_id
          }
        })
      },
      toDraftHistory() {
        this.$router.push({
          name: 'contract-history',
          params: {
            guarantee_project_id: this.guarantee_project_id
          }
        })
      },
    }
  }
</script>
