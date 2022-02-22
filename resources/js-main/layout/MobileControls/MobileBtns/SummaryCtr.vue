<template>
  <div>
    <li v-if="needComplete" class="header-controls__chat-option" @click="completeContract">
      <div class="header-controls__chat-op-innner">
        <img src="/img/icon/double-checkmarks_blue.svg" alt="terms">
        <div>Complete Contract</div>
      </div>
    </li>
    <a href="mailto:tom@erenovate.com">
      <li class="header-controls__chat-option">
        <div class="header-controls__chat-op-innner">
          <img src="/img/icon/support_blue.svg" alt="history">
          <div>Support</div>
        </div>
      </li>
    </a>
  </div>

</template>

<script>
  import {mapGetters} from 'vuex'

  export default {
    name: "SummaryCtr",
    data() {
      return {}
    },
    props: {
      role: {
        type: String,
      }
    },
    computed: {
      ...mapGetters({
        user: 'user',
        summary_contract: 'summary/contract',
        summary_loaded: 'summary/loaded'
      }),
      needComplete() {
        if (!this.summary_loaded) {
          return false
        }
        return this.summary_contract.status === 'finished' &&
          !this.summary_contract.ho_confirm_complete &&
          this.user.role === 'homeowner'
      },
    },
    methods: {
      completeContract() {
        let scroll_body = $(".component-body_scroll");
        let complete_button = $(".complete-button");

        scroll_body.animate({
          scrollTop: complete_button.offset().top
        }, 1000)
      },
    },
  }
</script>
