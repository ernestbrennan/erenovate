<template>
  <div>

    <template v-if="$route.name === 'summary'">
      <div class="summary-time__link"
           @click="to">
        VIEW
      </div>
    </template>

    <template v-else-if="$route.name === 'messages'">
      <v-btn depressed
             color="#1875F0"
             dark
             class="chat-message__btn-detail"
             @click="to">
        VIEW SUMMARY
      </v-btn>
    </template>

    <template v-else-if="$route.name === 'contract-history'">
      <button class="contract-history__view main-btn main-btn_full-blue"
              @click="to">
        VIEW
      </button>
    </template>

  </div>
</template>

<script>
  export default {
    data() {
      return {
        types: {
          summary_ready: 'summary_ready',
        },
      }
    },
    props: {
      notification: Object,
    },
    methods: {
      to() {
        return this.$router.push({
          name: 'summary',
          params: {
            guarantee_project_id: this.$route.params.guarantee_project_id,
          }
        });
      }
    }
  }
</script>
