<template>
  <div>
    <li class="header-controls__chat-option">
      <div class="header-controls__chat-op-innner"
           @click="proposeTerms"
      >
        <img src="/img/icon/terms_blue.svg" alt="terms">

        <div>Propose Different Terms</div>
      </div>
    </li>
    <li class="header-controls__chat-option">
      <div class="header-controls__chat-op-innner"
           @click="toDraftHistory"
      >
        <img src="/img/icon/history_blue.svg" alt="history">
        <div>Draft History</div>
      </div>
    </li>
    <!--<li class="header-controls__chat-option">-->
    <!--<div class="header-controls__chat-op-innner"-->
    <!--@click="toProject"-->
    <!--&gt;-->
    <!--<img src="/img/icon/job-post_blue.svg" alt="post-job">-->
    <!--<div>View Job Post</div>-->
    <!--</div>-->
    <!--</li>-->
    <li class="header-controls__chat-option">
      <div class="header-controls__chat-op-innner"
           @click="toMessages"
      >
        <img src="/img/icon/messages_blue.svg" alt="messages">
        <div>Messages</div>
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
      ...mapGetters(["user", "contract_draft", "guarantee_project", "contract"]),
      propose: {
        get() {
          return this.$store.state.currentContract.propose
        },
        set(val) {
          return this.$store.commit('set', {type: 'currentContract', data: {propose: val}})
        }
      },
    },
    methods: {
      checkRequiredComment(value) {
        let state = this[value].length > 0;
        if (!state) {
          $('.main-dialog__textarea').addClass('invalid-input')
        } else {
          $('.main-dialog__textarea').removeClass('invalid-input')
        }
        return state
      },
      proposeTerms() {
        if (this.contract.interlocutor_info.status !== 'new' && this.contract.interlocutor_info.status === 'pending') {
          this.$store.commit('set', {type: 'errorAlert', data: {type: 'error', text: 'Confirm User Info'}})
          return;
        }

        this.propose = true;
        this.$store.state.slideSearchContracts = false;
        this.$store.commit('set', {type: 'readOnly', data: false});
      },
      toMessages() {
        this.$store.state.slideSearchContracts = false;
        this.$router.push({
          name: 'messages',
          params: {
            guarantee_project_id: this.guarantee_project.id
          }
        })
      },
      toProject() {
        this.$store.state.slideSearchContracts = false;
        window.open(
          this.guarantee_project.project_link,
          '_blank'
        );
      },
      toDraftHistory() {
        this.$store.state.slideSearchContracts = false;
        this.$router.push({
          name: 'contract-history',
          params: {
            guarantee_project_id: this.guarantee_project.id
          }
        })
      },
      proposeDraft() {
        this.$store.state.slideSearchContracts = false;
        let state = false;
        if (this.dialogContractPropose) {
          state = this.checkRequiredComment('proposeComment')
        } else if (this.dialogProposalConfirm) {
          state = this.checkRequiredComment('proposalComment')
        }

        if (state) {
          api
            .post('contract-draft.proposeDraft', {
              contract: this.contract,
              contract_draft: this.contract_draft,
              comment: this.proposeComment
            })
            .then(response => {

              this.toMessages()
            });
        }
      },

    }
  }
</script>
