<template>
  <div class="hidden-md-and-up header-slot">
    <slot name="header-title"></slot>
    <template v-if="this.$route.name === 'welcome' ">
      <div class="header-slot__title">
        <img class="header-slot__title-img" :src="'/img/icon/contract_gray.svg'" alt="contract"><span
        class="header-slot__title-text">Welcome</span>
      </div>
    </template>
    <template v-else-if="this.$route.name === 'notes' || this.$route.name === 'messages'">
      <div class="messeges-and-notes__tabs">
        <v-btn
          :class="{active: this.$route.name === 'messages'}"
          flat
          class="messeges-and-notes__btn left"
          @click="toMessages"
        >
          MESSAGES
          <template v-if="chat.unread_messages_count !== 0 && typeof chat.unread_messages_count !== 'undefined'">
            <!-- if counter !== 0-->
            <span class="msg-counter">
              {{chat.unread_messages_count}}
            </span>
          </template>
        </v-btn>
        <v-btn
          :class="{active: this.$route.name === 'notes'}"
          flat
          class="messeges-and-notes__btn right"
          @click="toNotes"
        >
          NOTES
        </v-btn>
      </div>
    </template>
    <template v-else-if="this.$route.name === 'projects-list'">
      <div class="header-slot__title">
        <img class="header-slot__title-img" :src="'/img/icon/contracts_gray.svg'" alt="contracts"><span
        class="header-slot__title-text">Projects</span>
      </div>
    </template>
    <template v-else-if="this.$route.name === 'fileHistory'">
      <div class="header-slot__title">
        <img class="header-slot__title-img" src="/img/icon/fileshistory-header.svg" alt="contracts"><span
        class="header-slot__title-text">Files History</span>
      </div>
    </template>
    <template v-else-if="this.$route.name === 'new-draft'">
      <div class="header-slot__title">
        <img class="header-slot__title-img" :src="'/img/icon/contract_gray.svg'" alt="contract">
        <span class="header-slot__title-text">{{draft_name | substring_20}}</span>
      </div>
    </template>
    <template v-else-if="this.$route.name === 'contract-history'">
      <div class="header-slot__title">
        <img class="header-slot__title-img" :src="'/img/icon/contract_gray.svg'" alt="contract">
        <span class="header-slot__title-text">Project "{{this.guarantee_project.project_name | substring_20 }}"</span>
      </div>
    </template>
    <template v-else-if="this.$route.name === 'current-draft'">
      <div class="header-slot__title">
        <img class="header-slot__title-img" :src="'/img/icon/contract_gray.svg'" alt="contract">
        <span class="header-slot__title-text">Project "{{this.guarantee_project.project_name | substring_20 }}"</span>
      </div>
    </template>
    <template
      v-else-if="this.$route.name === 'contract-signed' || this.$route.name === 'list-invoice' || this.$route.name === 'history-invoice' || this.$route.name === 'current-invoice' || this.$route.name === 'completed-milestone'">
      <app-timeline></app-timeline>
    </template>
    <template v-else-if="this.$route.name ===  'create-invoice'">
      <app-timeline></app-timeline>
    </template>
    <template v-else-if="this.$route.name ===  'current-milestone'">
      <app-timeline></app-timeline>
    </template>
    <template v-else-if="this.$route.name ===  'edited-milestone'">
      <div class="header-slot__title">
        <img class="header-slot__title-img" src="/img/icon/contract_gray.svg" alt="contracts"><span
        class="header-slot__title-text">Editing Milestones</span>
      </div>
    </template>
    <template v-else-if="this.$route.name ===  'summary'">
      <app-timeline></app-timeline>
    </template>
    <template v-else-if="this.$route.name ===  'by-id-milestone'">
      <app-timeline></app-timeline>
    </template>
    <template v-else-if="this.$route.name ===  'issue'">
      <div class="header-slot__title">
        <img class="header-slot__title-img" src="/img/icon/contracts_gray.svg" alt="contracts"><span
        class="header-slot__title-text">issue #{{this.$store.state.issue.sequence}}</span>
      </div>
    </template>
    <template v-else-if="this.$route.name ===  'settings'">
      <div class="header-slot__title">
        <span class="header-slot__title-text">Settings</span>
      </div>
    </template>
    <template v-else-if="this.$route.name ===  'invite-ho'">
      <div class="header-slot__title">
        <img class="header-slot__title-img" src="/img/icon/contract_gray.svg" alt="contract">
        <span class="header-slot__title-text">Create Project Scope</span>
        <!--<span class="header-slot__title-text">Invite a client</span>-->
      </div>
    </template>

  </div>
</template>
<script>
  import {mapGetters} from 'vuex'
  import Timeline from './MobileTitle/Timeline'

  export default {
    components: {
      'app-timeline': Timeline
    },
    data() {
      return {
        data: this.$route.name
      }
    },
    computed: {
      ...mapGetters(["user", "chat", "guarantee_project", "contract_draft"]),
      draft_name() {
        let std = `Project ${this.$store.state.contract.id}`
        if (this.guarantee_project.project_name) {
          return `Project "${this.guarantee_project.project_name}"`
        }
        return std

      }
    },
    methods: {
      toMessages() {
        let targetId = this.$route.params.guarantee_project_id;

        this.$router.push({
          name: 'messages',
          params: {guarantee_project_id: targetId}
        })
      },
      toNotes() {
        let targetId = this.$route.params.guarantee_project_id;
        this.$router.push({
          name: 'notes',
          params: {guarantee_project_id: targetId}
        })
      },
    },
  }
</script>
