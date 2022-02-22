<template>
  <div class="contact-draft__milestones-list">
    <template v-if="desktop_only && !readOnly">

      <milestone-draggable v-model="milestones">
        <div :key="milestone.id" v-for="(milestone, index) in milestones" class="contract-draft__el-box">
          <milestone
            :open="openMod(index)"
            :milestone="milestone"
            :index="index"
            :readOnly="readOnly"
            v-on:removeMilestone="removeMilestone"
          />
        </div>
      </milestone-draggable>

    </template>
    <template v-else>
      <div :key="milestone.id" v-for="(milestone, index) in milestones" class="contract-draft__el-box">
        <milestone
          :open="openMod(index)"
          :milestone="milestone"
          :index="index"
          :readOnly="readOnly"
          v-on:removeMilestone="removeMilestone"
        />
      </div>
    </template>
    <button
      class="contact-draft__milestones-add main-btn main-btn_full-blue"
      v-if="!readOnly"
      @click="addNewMilestone"
    >
      ADD MILESTONE
    </button>

    <contract-summary :read_only="readOnly"/>
  </div>
</template>
<script>
  import Milestone from "./Milestone.vue"
  import Summary from "../Summary/Summary.vue"
  import draggable from 'vuedraggable'

  export default {
    components: {
      'milestone': Milestone,
      'contract-summary': Summary,
      'milestone-draggable': draggable
    },
    props: [
      'milestones',
      'readOnly',
    ],
    data() {
      return {
        new_milestone: {
          status: 'pending',
          milestone_content: {
            title: '',
            description: '',
            price: 0,
            start_date: null,
            end_date: null,
            status: 'pending',
            material_supply_side: 'contractor',
            materials_provide_on: 'contract',
            batches: [],
            materials: [],
            material_files: [],
          },
        }
      }
    },
    computed: {
      desktop_only() {
        return window.innerWidth > 1024
      },
    },
    methods: {
      addNewMilestone: function () {
        this.milestones.push(JSON.parse(JSON.stringify(this.new_milestone)));
      },
      openMod: function (index) {
        return this.milestones.length - 1 === index
      },
      removeMilestone: function (index) {

        this.$delete(this.milestones, index);

        if (!this.milestones.length) {

          this.addNewMilestone()
        }
      },
      slideToEdit(edit_id) {
        var container = $('.contract-comp__body'),
          scrollTo = $('.contract-comp__body .milestone_' + edit_id).eq(0);
        if (typeof scrollTo !== 'undefined') {
          container.animate({
            scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop() - 100
          }, 1000);
        }
      },
      mountedEditSlide() {
        if (this.$route.name === 'edited-milestone') {
          const sq = this.$route.query.edit_id;
          if (sq === undefined || sq === null) {
            return false
          }
          const timer = setTimeout(() => {
            this.slideToEdit(sq);
            clearTimeout(timer);
          }, 500)
        }
      },

    },
    mounted() {
      this.mountedEditSlide();
    },
  }
</script>
