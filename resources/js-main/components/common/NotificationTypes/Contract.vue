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
        {{messageButton}}
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
          contract_first_accepted: 'contract_first_accepted',
          contract_both_accepted: 'contract_both_accepted',
          contract_signed: 'contract_signed',
          contract_completed: 'contract_completed',
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
    computed: {
      messageButton() {
        if (this.notification.type === this.types.contract_signed) {
          return 'VIEW PROJECT'
        }

        return 'VIEW DETAILS'
      }
    },
    methods: {
      to() {
        // if (this.notification.type === this.types.contract_signed) {
        //     return window.open(
        //         'https://erenovate.com/homeowners/Guarantee-Guided-Tour',
        //         '_blank'
        //     );
        // }

        if (this.notification.type === this.types.contract_completed) {
          return this.$router.push({
            name: 'summary',
            params: {guarantee_project_id: this.$route.params.guarantee_project_id}
          });
        } else if ((this.notification.type === this.types.contract_first_accepted || this.notification.type === this.types.contract_both_accepted) && this.notification.contract.status === 'pending') {
          return this.$router.push({
            name: 'current-draft',
            params: {guarantee_project_id: this.$route.params.guarantee_project_id}
          });
        }

        return this.$router.push({
          name: 'current-milestone',
          params: {guarantee_project_id: this.$route.params.guarantee_project_id}
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
