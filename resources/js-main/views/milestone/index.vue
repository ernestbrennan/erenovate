<template>
  <div class="contract-comp">
    <div class="row row-mobile">

      <div class="col-xl-9 col-lg-12 col-12 custom-col-mobile">
        <controls
          :milestone="current_milestone"
          v-on:getMilestone="getMilestone"
          v-on:getEditedMilestone="getEditedMilestone"
        />
        <milestone-builder :milestone="current_milestone" :edited_milestone="edited_milestone"/>
      </div>

      <div class="col-xl-3 hidden-md-and-down">
        <app-timeline :timeline="timeline"/>
      </div>

    </div>
  </div>
</template>

<script>
  import {mapGetters} from 'vuex'
  import Timeline from "@/components/common/Timeline/Timeline"
  import Controls from './Controls'
  import MilestoneBuilder from '../../builders/MilestoneBuilder/MilestoneBuilder'
  import {ParentMixin} from '@/components/common/Mixins/glValidate/mixins'

  export default {
    mixins: [ParentMixin],
    components: {
      'app-timeline': Timeline,
      'milestone-builder': MilestoneBuilder,
      'controls': Controls,
    },
    data() {
      return {
        guarantee_project_id: this.$route.params.guarantee_project_id,
        edited_milestone: null,
      }
    },
    computed: {
      ...mapGetters(["user", "timeline", 'current_milestone', 'read_only_milestone']),
    },
    created() {
      this.getMilestone();
    },

    methods: {
      getMilestone() {
        this.$store.state.global_loader = true;
        this.$store.state.read_only_milestone = true;
        this.edited_milestone = null;

        if (this.$route.name === 'current-milestone') {

          this.$store.dispatch('getCurrentMilestone', this.guarantee_project_id).then(response => {
            this.$store.state.global_loader = false;
          });
        }

        if (this.$route.name === 'by-id-milestone') {

          this.$store.dispatch('getMilestoneById', {
            milestone_id: this.$route.params.milestone_id,
            version: this.$route.query.status !== 'pending' ? this.$route.params.version : null,
          }).then(response => {
            this.$store.state.global_loader = false;

            if (this.$route.query.status === 'pending') {
              this.getEditedMilestone();
            }
          });
        }
      },
      getEditedMilestone() {
        this.$store.state.global_loader = true;
        this.$store.state.read_only_milestone = true;
        this.edited_milestone = this.current_milestone;

        this.$store.dispatch('getEditedMilestone', this.current_milestone.id).then(response => {
          this.$store.state.global_loader = false;
        });
      }
    }
  }
</script>
