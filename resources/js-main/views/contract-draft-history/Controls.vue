<template>
  <div ref="contractHeader" class="contract-comp__header">
    <div class="g-flex g-flex_row g-flex_wrap margin-10">

      <div class="contract-header__title hidden-sm-and-down">
        Project Draft #{{guarantee_project.id}}
      </div>
      <button class="contract-header__contract-label">
        <img src="/img/icon/plus_white.svg" alt="plus">
        {{contract_draft.created_at | moment("MMM D h:mm:ss A")}}
      </button>

      <v-spacer class="hidden-sm-and-down"></v-spacer>
      <template v-if="desktopOnly">
        <button class="main-btn main-btn_border-blue" @click="toHistory">
          DRAFTS HISTORY
        </button>
        <v-spacer class="hidden-md-and-up"></v-spacer>
        <button class="main-btn main-btn_full-blue" @click="toButton">
          {{contractDraftButton}}
        </button>
      </template>
    </div>
  </div>
</template>
<script>
  import {mapGetters} from 'vuex'

  export default {
    computed: {
      ...mapGetters(["user", "contract_draft", "guarantee_project"]),

      desktopOnly() {
        return window.innerWidth >= 992
      },

      contractDraftButton() {
        if (this.guarantee_project.contract_status === 'signed') {
          return 'CURRENT MILESTONE';
        }
        if (this.guarantee_project.has_contract_draft) {
          return 'CURRENT VERSION';
        }
        return 'MESSENGER'
      },
    },
    methods: {
      toButton() {
        let targetId = this.guarantee_project.id;

        if (this.guarantee_project.contract_status === 'signed') {

          return this.$router.push({
            name: 'current-milestone',
            params: {guarantee_project_id: targetId}
          })

        } else if (this.guarantee_project.has_contract_draft) {

          return this.$router.push({
            name: 'current-draft',
            params: {guarantee_project_id: targetId}
          })
        } else {

          return this.$router.push({
            name: 'messages',
            params: {guarantee_project_id: targetId}
          });
        }
      },

      toHistory() {
        this.$router.push({
          name: 'contract-history',
          params: {
            guarantee_project_id: this.guarantee_project.id
          }
        })
      },
    },
  }
</script>
