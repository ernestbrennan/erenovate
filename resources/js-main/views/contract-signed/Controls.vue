<template>
  <div ref="contractHeader" class="contract-comp__header">
    <div class="g-flex g-flex_row g-flex_wrap margin-10 g-flex_vertical-center">

      <div class="contract-header__title hidden-sm-and-down">
        Project Scope "{{ guarantee_project.project_name | substring_20}}"
      </div>

      <template v-if="has_edited_milestone">
        <div class="contract-header__contract-label hidden-sm-and-down">
          Edit Been Proposed
        </div>
        <v-spacer class="hidden-sm-and-down"></v-spacer>

        <div class="contract-header__title hidden-md-and-up">
          Edit Been Proposed
        </div>
        <v-spacer class="hidden-md-and-up"></v-spacer>
        <button class="main-btn main-btn_full-green hidden-md-and-up contract-signed__view-edit-btn"
                @click="toEditedMilestone">
          {{text_view_edit}}
        </button>
        <button class="main-btn main-btn_border-blue-deep hidden-sm-and-down" @click="toEditedMilestone">
          {{text_view_edit}}
        </button>

      </template>

      <template v-else>
        <v-spacer class="hidden-sm-and-down"></v-spacer>

        <button class="main-btn main-btn_border-blue-deep" @click="toInvoices" v-if="has_invoices">
          REQUESTS
        </button>
        <button class="main-btn main-btn_border-blue-deep hidden-sm-and-down" @click="exportContractDraft">
          <template v-if="download_pdf_loading === true">
            <v-progress-circular
              indeterminate
              color="#1875F0"
            ></v-progress-circular>
          </template>
          <template v-else>
            DOWNLOAD PDF
          </template>
        </button>
      </template>

      <v-menu left offset-y nudge-top="-16" class="hidden-sm-and-down">
        <button class="main-btn contract-header__dropmenu-box main-btn_border-blue-deep" slot="activator">
          <span class="dropmenu-box__dots"></span>
          MORE
        </button>

        <div class="contract-header__drop-target">
          <ul class="option-list-dropdown">
            <li class="option-list-dropdown__el" @click="toInvoices" v-if="has_invoices">
              <img src="/img/icon/invoices_blue.svg" alt="history">
              <span>Requests</span>
            </li>
            <li class="option-list-dropdown__el" @click="exportContractDraft">
              <img src="/img/icon/pdf-icon_blue.svg" alt="messages">
              <span>Download PDF</span>
            </li>
            <li class="option-list-dropdown__el" @click="toDraftHistory">
              <img src="/img/icon/history_blue.svg" alt="history">
              <span>Project History</span>
            </li>
            <li class="option-list-dropdown__el" @click="toMessages">
              <img src="/img/icon/messages_blue.svg" alt="messages">
              <span>Messages</span>
            </li>
            <li class="option-list-dropdown__el" @click="toFileHistory">
              <img src="/img/icon/files_blue.svg" alt="terms">
              <span>Files History</span>
            </li>
          </ul>
        </div>
      </v-menu>
    </div>
  </div>

</template>
<script>
  import {mapGetters} from 'vuex'
  import export_contract_draft from '@/components/mixins/export/contract_draft'

  export default {
    mixins: [export_contract_draft],
    data() {
      return {
        guarantee_project_id: this.$route.params.guarantee_project_id
      }
    },
    computed: {
      ...mapGetters(["contract_draft", "guarantee_project", "contract"]),

      text_view_edit() {
        return (window.innerWidth >= 768) ? 'VIEW EDIT' : 'VIEW'
      },
      has_invoices() {
        return this.contract_draft.invoices_count || false;
      },
      has_edited_milestone() {
        return this.contract_draft.milestone_edited_id
      }
    },
    methods: {

      toInvoices() {
        this.$router.push({
          name: 'list-invoice',
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
      toMessages() {
        this.$router.push({
          name: 'messages',
          params: {
            guarantee_project_id: this.guarantee_project_id
          }
        })
      },
      toFileHistory() {
        this.$router.push({
          name: 'fileHistory',
          params: {
            guarantee_project_id: this.guarantee_project_id
          }
        })
      },
      toEditedMilestone() {
        this.$router.push({
          name: 'by-id-milestone',
          params: {
            guarantee_project_id: this.guarantee_project_id,
            milestone_id: this.contract_draft.milestone_edited_id
          }
        })
      }
    },
  }
</script>
