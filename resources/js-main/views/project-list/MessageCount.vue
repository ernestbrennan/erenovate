<template>
  <span class="contract-list__msg-counter" v-if="contract.chat.unread_messages_count">
    {{contract.chat.unread_messages_count}}
  </span>
</template>

<script>
  import {mapGetters} from 'vuex'

  export default {
    props: ['contract'],
    computed: {
      ...mapGetters(["user", "contracts"])
    },
    created() {
      Echo.channel('Chat.' + this.contract.chat.id)
        .listen('.new_message', (event) => {
          this.$store.dispatch('receiveMessage', event);
        });
    },
  }
</script>
