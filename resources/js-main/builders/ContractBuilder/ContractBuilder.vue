<template>
  <div class="contract-comp__body component-body_scroll scrollbar"
       :class="{'contract-comp__body_signed': body_signed}"
       :style="{height: componetConteinerHeight}">

    <div class="contract-draft__el-box" v-if="show_user_info">
      <div class="contract-draft__title-box" @click="toggleStageArg">
        <div class="contract-draft__title">Project is Between</div>
        <img class="contract-draft__curret" :src="'/img/icon/caret-icon_gray.svg'" :class="{active: arguments_toggle}">
      </div>

      <div ref="arguments_box" class="contract-draft__slide-main-box">
        <stage-agreements :current="contract.current_user_info" :interlocutor="contract.interlocutor_info"/>
      </div>
    </div>

    <div class="contract-draft__el-box subject-matter__scroll-to">
      <div class="contract-draft__title-box" @click="changeSubjectMatter">
        <div class="contract-draft__title">Project Details</div>
        <img class="contract-draft__curret" :src="'/img/icon/caret-icon_gray.svg'" :class="{active: subject_matter}">
      </div>

      <div
        class="contract-draft__slide-main-box contract-draft__gray-box"
        data-v-step="ccd-17"
        :class="class_full"
        ref="subject_matter_box"
      >

        <subject-matter :contract_draft="contract_draft" :readOnly="readOnly"/>

        <template v-if="contract_draft.type === 'single' && (!readOnly || has_materials)">

          <contract-materials
            :milestone_content="firstMilestone().milestone_content"
            :readOnly="readOnly"
            :milestone_index="0"
          />
        </template>

      </div>
    </div>

    <template v-if="contract_draft.type === 'several'">
      <milestones :milestones="contract_draft.milestones" :readOnly="readOnly"/>
    </template>
  </div>
</template>
<script>
  import StageAgreement from "./StageAgreement/StageAgreement"
  import SubjectMatter from "./SubjectMatter/SubjectMatter"
  import Milestones from "./Milestone/Milestones"
  import ContractMaterials from "./Material/Materials.vue"

  import {ContainerHeight} from '@/components/common/Mixins/builder'
  import {mapGetters} from 'vuex'

  export default {
    mixins: [ContainerHeight],
    components: {
      'stage-agreements': StageAgreement,
      'subject-matter': SubjectMatter,
      'milestones': Milestones,
      'contract-materials': ContractMaterials,
    },
    data() {
      return {
        arguments_toggle: true,
        subject_matter: true,
        user_info_exclude_page: ['history-draft']
      }
    },
    props: {
      contentHeight: [Boolean, Number],
      readOnly: Boolean,
      contract_draft: Object,
      contract: Object,
      body_signed: {
        type: Boolean,
        default: false
      },
    },
    computed: {
      ...mapGetters(["user"]),

      class_full() {
        return this.$route.name !== 'contract-signed' ? 'contract-draft__gray-box_full' : false
      },
      has_materials() {
        return this.firstMilestone().milestone_content.materials.length || this.firstMilestone().milestone_content.material_files.length;
      },
      show_user_info() {
        return !this.user_info_exclude_page.includes(this.$route.name)
      }
    },
    methods: {
      firstMilestone() {
        return this.contract_draft.milestones[0];
      },
      changeSubjectMatter() {
        this.subject_matter = !this.subject_matter;
        $(this.$refs.subject_matter_box).slideToggle()
      },
      toggleStageArg() {
        this.arguments_toggle = !this.arguments_toggle;
        $(this.$refs.arguments_box).slideToggle();
      },
    },
  }
</script>
