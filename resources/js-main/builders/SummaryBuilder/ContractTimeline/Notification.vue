<template>
  <div class="summary-time">
    <div class="summary-time__info">
      <div class="summary-time__date">
        {{ message.created_at | moment("MM/DD/YY") }} <br> {{ message.created_at | moment(" h:mm a") }}
      </div>
    </div>
    <div class="summary-time__icon">
      <img :src="'/img/icon/'+ iconNotification(message.notification, 'gray')" alt="">
    </div>
    <div class="summary-time__text">
      <!-- title -->
      <template v-if=" checkType('contract')">
        <contract-type
          :title="message.notification.title"
          activator_type="string"
          :notification="message.notification"/>
      </template>

      <template v-else-if="checkType('contract_draft')">
        <contract-draft-type
          :title="message.notification.title"
          activator_type="string"
          :notification="message.notification"/>
      </template>

      <template v-else-if="checkType('milestone_content')">
        <milestone-content-type
          :title="message.notification.title"
          activator_type="string"
          :notification="message.notification"/>
      </template>

      <template v-else-if="checkType('invoice')">
        <invoice-type
          :title="message.notification.title"
          activator_type="string"
          :notification="message.notification"/>
      </template>
      <template v-else>
        <div class="summary-time__title disabled">
          {{ message.notification.title }}
        </div>
      </template>

      <!-- button -->
      <template v-if=" checkType('contract')">
        <contract-type :notification="message.notification"/>
      </template>

      <template v-else-if="checkType('contract_draft')">
        <contract-draft-type :notification="message.notification"/>
      </template>

      <template v-else-if="checkType('milestone_content')">
        <milestone-content-type :notification="message.notification"/>
      </template>

      <template v-else-if="checkType('invoice')">
        <invoice-type :notification="message.notification"/>
      </template>

      <div style="width: 100%;" class="hidden-sm-and-up">
        <div class="summary-time__date"> {{ message.created_at | moment("MM/DD/YY h:mm a") }}</div>
      </div>
    </div>
  </div>
</template>

<script>
  import Invoice from '@/components/common/NotificationTypes/Invoice';
  import Contract from '@/components/common/NotificationTypes/Contract';
  import ContractDraft from '@/components/common/NotificationTypes/ContractDraft';
  import MilestoneContent from '@/components/common/NotificationTypes/MilestoneContent';

  import {NotificationTypes} from '@/components/common/Mixins/notification_types'
  import {IconByNotification} from '@/components/common/Mixins/notification_types'

  export default {
    mixins: [NotificationTypes, IconByNotification],
    components: {
      'contract-type': Contract,
      'contract-draft-type': ContractDraft,
      'milestone-content-type': MilestoneContent,
      'invoice-type': Invoice,
    },
    data() {
      return {
        guarantee_project_id: this.$route.params.guarantee_project_id,
      }
    },
    props: {
      message: Object,
    },
    methods: {},
  }
</script>

<style scoped>
  .summary-time__icon svg path {
    fill: gray;
  }
</style>
