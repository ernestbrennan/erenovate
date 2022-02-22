<template>
  <div class="summary-block">
    <h3 class="summary-block__border-title sum-tour-3">Project Timeline</h3>
    <ul class="summary-timeline-box">

      <li :key="message.id" v-for="message in history" class="summary-time-el">

        <notification :message="message"/>

      </li>

    </ul>
  </div>
</template>

<script>
  import Notification from './Notification'
  import {mapGetters} from 'vuex'

  export default {
    components: {
      'notification': Notification,
    },
    computed: {
      ...mapGetters({
        history: 'summary/history'
      })
    }
  }
</script>
