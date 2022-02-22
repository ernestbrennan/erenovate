<template>
  <div ref="contractHeader" class="contract-comp__header contract-comp__header_summery">
    <div class="g-flex g-flex_row g-flex_wrap margin-10 g-flex_vertical-center"
         :class="{'g-flex_summary-page': !needSpace}">
      <div class="contract-header__title" :class="{'hidden-sm-and-down': summary_contract.status === 'finished'}">
        Project "{{ project_name | substring_title}}" Summary
      </div>
      <div class="sum-tour-1 contract-header__contract-label" v-if="summary_contract.status === 'finished'">
        Project Completed Approval Pending
      </div>
      <!--<v-spacer></v-spacer>-->
      <div class="sum-tour-2" v-if="!needConfirmComplete"></div>
      <button
        class="sum-tour-2 main-btn main-btn_border-blue-deep hidden-sm-and-down"
        v-if="needConfirmComplete"
        @click="scrollDown"
      >
        COMPLETE PROJECT
      </button>
    </div>
    <template v-if="getTour">
      <v-tour name="tourSummary" :steps="steps" :options="optionsVueTour" :callbacks="tourCallbacks"></v-tour>
    </template>
  </div>

</template>
<script>
  import {mapGetters} from 'vuex'
  import SummaryTour from '@/plugins/tour/summary'
  import InitTour from '@/plugins/tour/init'

  export default {
    mixins: [SummaryTour, InitTour],
    filters: {
      substring_title: function (value) {
        if (value.length >= 15) {
          return value.substring(0, 12) + '...'
        }
        return value;
      }
    },
    computed: {
      needConfirmComplete() {
        return this.summary_contract.status === 'finished' &&
          !this.summary_contract.ho_confirm_complete &&
          this.user.role === 'homeowner'
      },
      project_name() {
        return (this.guarantee_project.project_name.length > 20) ?
          this.guarantee_project.project_name.substr(0, 17) + '...' :
          this.guarantee_project.project_name;
      },
      needSpace() {
        return this.summary_contract.status === 'finished' && this.user.role === 'contractor'
      },
      ...mapGetters({
        user: 'user',
        guarantee_project: 'guarantee_project',
        summary_contract: 'summary/contract'
      })
    },
    methods: {
      scrollDown() {
        let scroll_body = $(".component-body_scroll");
        let complete_button = $(".complete-button");

        scroll_body.animate({
          scrollTop: complete_button.offset().top
        }, 1000)
      }
    },
  }
</script>
