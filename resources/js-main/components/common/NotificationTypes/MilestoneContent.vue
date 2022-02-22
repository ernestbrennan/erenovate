<template>
  <div>

    <template v-if="$route.name === 'summary'">
      <template v-if="activator_type === 'button'">
        <div class="summary-time__link"
             @click="to">
          VIEW
        </div>
      </template>
      <template>
        <div class="summary-time__title" @click="toMobile">
          {{ title }}
        </div>
      </template>
    </template>

    <template v-else-if="$route.name === 'messages'">
      <v-btn depressed
             color="#1875F0"
             dark
             class="chat-message__btn-detail"
             @click="to">
        VIEW DETAILS
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
          milestone_content_edited: 'milestone_content_edited',
          milestone_content_accepted: 'milestone_content_accepted',
          milestone_content_rejected: 'milestone_content_rejected',
        },
      }
    },
    props: {
      notification: Object,
      activator_type: {
        require: false,
        default: 'button'
      },
      title: {
        type: String
      },
    },
    methods: {
      to() {
        return this.$router.push({
          name: 'by-id-milestone',
          params: {
            guarantee_project_id: this.$route.params.guarantee_project_id,
            milestone_id: this.notification.milestone_content.milestone_id,
            version: this.notification.milestone_content.version,
          },
          query: {
            status: this.notification.milestone_content.status
          }
        });
      },
      toMobile() {
        if (window.innerWidth > 768) {
          return false
        } else {
          this.to()
        }
      }

    }
  }
</script>
