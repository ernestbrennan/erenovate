<template>
  <div class="contract-comp">
    <div class="row row-mobile">
      <div class="col-xl-9 col-lg-12 col-12 custom-col-mobile">

        <template v-if="summary_loaded && !waitingState">

          <controls/>

          <summary-builder/>

        </template>

        <template v-else>
          <div class="summary-empty scrollbar" :style="{height: emptyHeight}">
            <div class="summary-empty__inner" :style="{'min-height': emptyHeight}">
              <div class="summary-empty__block">
                <img src="/img/empty-summary.svg" alt="empty-summary">
                <h4 class="common-h4 text-xs-center">
                  Project Summary
                </h4>
                <p class="common-p text-xs-center">
                  Once Project is completed, this page
                  will summarize the entire history of events.
                </p>
                <button class="main-btn main-btn_full-blue" @click="toCurrentMilestone">
                  GO TO CURRENT MILESTONE
                </button>
              </div>
            </div>
          </div>
        </template>
      </div>
      <div class="col-xl-3 hidden-md-and-down">

        <timeline/>

      </div>
    </div>
  </div>
</template>

<script>
  import Timeline from "@/components/common/Timeline/Timeline"
  import Controls from './Controls'
  import SummaryBuilder from "@/builders/SummaryBuilder/SummaryBuilder";

  import {mapGetters} from 'vuex'

  export default {
    components: {
      'controls': Controls,
      'summary-builder': SummaryBuilder,
      'timeline': Timeline,
    },
    data() {
      return {
        emptyHeight: false,
      }
    },
    computed: {
      waitingState() {
        return ['pending', 'signed'].includes(this.summary_contract.status)
      },
      ...mapGetters({
        summary_loaded: 'summary/loaded',
        summary_contract: 'summary/contract'
      })
    },
    methods: {
      emptyHeightResize() {
        this.emptyHeight = this.$store.state.containerHeight + 'px';
        return true
      },
      toCurrentMilestone() {
        return this.$router.push({
          name: 'current-milestone',
          params: {guarantee_project_id: this.$route.params.guarantee_project_id}
        })
      },
    },
    created() {
      this.$store.state.global_loader = true;
      this.$store.dispatch('summary/byProject', this.$route.params.guarantee_project_id).then(() => {
        this.$store.state.global_loader = false;
      });
    },
    mounted() {
      this.emptyHeightResize();
      window.addEventListener('resize', this.emptyHeightResize)
    },
    beforeDestroy() {
      this.$store.commit('summary/CLEAR_SUMMARY')
    }
  }
</script>
