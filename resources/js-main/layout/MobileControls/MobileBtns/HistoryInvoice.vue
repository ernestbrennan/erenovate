<template>
  <div>
    <li class="header-controls__chat-option" @click="exportInvoice">
      <div class="header-controls__chat-op-innner">
        <img src="/img/icon/pdf-icon_blue.svg" alt="messages">
        <div>Export</div>
      </div>
    </li>
    <!-- <li class="header-controls__chat-option">
        <div class="header-controls__chat-op-innner">
            <img src="/img/icon/knowledge_blue.svg" alt="terms">
            <div>Knowlage Base</div>
        </div>
    </li> -->
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
  import export_invoice from '@/components/mixins/export/invoice.js'

  export default {
    mixins: [export_invoice],
    data() {
      return {}
    },
    computed: {
      ...mapGetters(['invoice']),
    },

  }
</script>
