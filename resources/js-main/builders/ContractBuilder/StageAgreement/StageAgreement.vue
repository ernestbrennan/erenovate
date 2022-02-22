<template>
  <div class="row">
    <div data-v-step="ccd-4" class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">

      <current-info :current="current" :contract_status="contract.status"/>

    </div>

    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">

      <invite-info v-if="is_invite_page"/>

      <interlocutor-info v-else :interlocutor="interlocutor" :contract_status="contract.status"/>

    </div>
  </div>
</template>
<script>
  import InviteInfo from './InviteInfo'
  import CurrentInfo from './CurrentInfo'
  import InterlocutorInfo from './InterlocutorInfo'

  import {mapGetters} from 'vuex'

  export default {
    components: {
      'invite-info': InviteInfo,
      'current-info': CurrentInfo,
      'interlocutor-info': InterlocutorInfo
    },
    props: [
      'current',
      'interlocutor',
    ],
    computed: {
      ...mapGetters(["contract"]),

      is_invite_page() {
        return this.$route.name === 'invite-ho'
      }
    }
  }
</script>
