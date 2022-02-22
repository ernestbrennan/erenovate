<template>
  <div>
    <div class="contract-draft__input-box">
      <v-tooltip right>
        <label
          class="contract-draft__input-label"
          :class="{'invalid-input':  errors.has('project title') }"
          slot="activator"
        >
          Project Title
          <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'" alt="">
        </label>
        <span>Name of Project identifying the project you submitted using the Post My Project tool.</span>
      </v-tooltip>

      <input
        placeholder="Enter Project Job Title Here"
        class="contract-draft__input"
        name="project title"
        type="text"

        :class="{'invalid-input':  errors.has('project title'),'scroll__invalid-input':  errors.has('project title') }"
        @focus="clearErrors('project title')"
        v-validate="'required'"
        :disabled="readOnly"
        key="contract_title"
        v-model="title"
      >

      <div v-if="errors.has('project title')" class="invalid-message">
        {{ errors.first('project title') }}
      </div>

    </div>
    <div class="contract-draft__input-box">

      <v-tooltip right>
        <label
          class="contract-draft__input-label"
          data-v-step="ccd-7"
          :class="{'invalid-input':  errors.has('project description') }"
          slot="activator"
        >
          Project Description
          <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'">
        </label>
        <span>
          Provide an overview of the project such as the overall objectives. <br>
          Be succinct, and to the point.
        </span>
      </v-tooltip>

      <textarea
        placeholder="Enter Brief Job Description"
        class="contract-draft__textarea"
        name="project description"
        ref="textareaDesc"

        :class="{'invalid-input':  errors.has('project description'),'scroll__invalid-input':  errors.has('project description') }"
        @focus="clearErrors('project description')"
        key="contract_description"
        v-validate="'required'"
        v-model="description"
        :disabled="readOnly"
      >
      </textarea>

      <div v-if="errors.has('project description')" class="invalid-message">
        {{ errors.first('project description') }}
      </div>
    </div>

    <v-tooltip right>
      <label slot="activator" class="contract-draft__input-label">Signed Contract
        <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'">
      </label>
      <span>
        Attaching Signed Contract between parties here is
        required to send Project Scope to client, and is
        required to ensure project is covered by the eRenovate
        Guarantee.
      </span>
    </v-tooltip>

    <contract-attachment data-v-step="ccd-8" :readOnly="readOnly" :contract_draft="contract_draft"/>

    <template v-if="has_batches_title">
      <v-tooltip right>
        <label slot="activator" class="contract-draft__input-label">
          Project Related Files
          <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'">
        </label>
        <span>Upload one or more files to share important project related documents and images</span>
      </v-tooltip>

    </template>

    <file-attachments data-v-step="ccd-8" id_dropzone="subject_1" :readOnly="readOnly" :batches="batches"/>

    <template v-if="!readOnly">

      <v-tooltip right>
        <label slot="activator" class="contract-draft__input-label">Project Milestones
          <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'" alt="">
        </label>
        <span>
          The Project milestones identify the key points or <br>
          stages along the course of the project, to track <br>
          progress and ensure the key deliverables are being <br>
          achieved for each stage. A simple project can have a <br>
          Single Milestone versus Several Milestones for a <br>
          larger project as agreed to by both parties.
        </span>
      </v-tooltip>

      <div class="toggle-buttons toggle-buttons_mobile-column" data-v-step="ccd-11">
        <button
          class="toggle-buttons__el left"
          data-v-step="ccd-12"
          :class="{active: contract_draft.type === 'single'}"
          @click="changeType('single')"
        >
          SINGLE MILESTONE
        </button>
        <button
          class="toggle-buttons__el right"
          :class="{active: contract_draft.type === 'several'}"
          @click="changeType('several')"
        >
          SEVERAL MILESTONES
        </button>
      </div>
    </template>
    <template v-if="contract_draft.type === 'single'">
      <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
          <div class="contract-draft__input-box">
            <v-tooltip right>
              <label
                class="contract-draft__input-label"
                :class="{'invalid-input':  errors.has('start date') }"
                slot="activator"
              >
                Approx. start date
                <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'" alt="">
              </label>
              <span>The expected starting date for this specific Milestone.</span>
            </v-tooltip>
            <input-date
              ref="start_date"
              v-model="contract_draft.milestones[0].milestone_content.start_date"
              :isValidate="true"
              label="start date"
              @focus="clearErrors('start date')"
              :readOnly="datePickerDisabled('startDatemenu')"
              key="end_date_subMetter"
              :error="errors.first('start date')"
              v-validate="'required'"
              @isOpen="setDisabled($event,'startDatemenu')"
            >
            </input-date>
          </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
          <div class="contract-draft__input-box">
            <v-tooltip right>
              <label class="contract-draft__input-label"
                     :class="{'invalid-input':  errors.has('end date'),'scroll__invalid-input':  errors.has('end date') }"
                     slot="activator">
                Approx. end date
                <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'" alt="">
              </label>
              <span>The estimated end date for this specific Milestone.</span>
            </v-tooltip>
            <input-date
              ref="end_date"
              v-model="contract_draft.milestones[0].milestone_content.end_date"
              :isValidate="true"
              label="end date"
              @focus="clearErrors('end date')"
              :setMinDate="contract_draft.milestones[0].milestone_content.start_date"
              :readOnly="datePickerDisabled('endDatemenu')"
              key="end_date_subMetter"
              :error="errors.first('end date')"
              v-validate="'required|earlier_date:start_date'"
              @isOpen="setDisabled($event,'endDatemenu')"
            >
            </input-date>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
          <div class="contract-draft__input-box">
            <v-tooltip right>
              <label
                class="contract-draft__input-label"
                :class="{'invalid-input':  errors.has('first_milestone_price') }"
                data-v-step="ccd-16"
                slot="activator"
              >
                {{ title_contract_price }}
                <img class="contract-draft__tooltip" src="/img/icon/info-icon_gray.svg">
              </label>
              <span>
                The total cost specific to complete the <b>project </b>or<br>
                work outlined in the Project Contract. Any project <br>
                changes during the project can increase the total <br>
                project cost at project completion.
              </span>
            </v-tooltip>
            <input
              :class="{'invalid-input':  errors.has('first_milestone_price') ,'scroll__invalid-input':  errors.has('first_milestone_price')}"
              placeholder="Enter Contract Price Here"
              class="contract-draft__input"
              name="first_milestone_price"
              @focus="clearErrors('first_milestone_price')"
              :disabled="readOnly"
              key="first_milestone_price"
              v-money="money"
              v-model.lazy="price"
              v-validate="'required|formatted_price'"
            >
            <div v-if="errors.has('first_milestone_price')" class="invalid-message">
              {{ errors.first('first_milestone_price') }}
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>
<script>
  import FileAttachments from "@/components/common/FileAttachments.vue"
  import ContractAttachment from "@/components/common/ContractAttachment.vue"
  import child_validator from "@/components/mixins/validator/base/child_validator";
  import DatePicker from '@/components/common/elements/DatePicker'
  import {nextTickResize, resizeTextareaReadOnly} from "@/components/common/Mixins/textarea";
  import {minDate} from "@/components/common/Mixins/datepicker";
  import {money} from "@/components/common/Mixins/money";

  export default {
    mixins: [child_validator, resizeTextareaReadOnly, nextTickResize, minDate, money],
    components: {
      'file-attachments': FileAttachments,
      'contract-attachment': ContractAttachment,
      'input-date': DatePicker
    },
    data() {
      return {
        endDatemenu: false,
        startDatemenu: false,
      }
    },
    props: [
      'contract_draft',
      'readOnly'
    ],
    computed: {
      has_batches_title() {
        if (this.readOnly && this.contract_draft.batches.length > 0) {
          return true
        } else if (this.readOnly === false) {
          return true
        } else {
          return false
        }
      },
      title_contract_price() {
        return this.contract_draft.type === 'single' ? 'Project Price, CAD' : 'Project Basic Price, CAD';
      },
      title: {
        get: function () {
          return this.getProp('title')
        },
        set: function (value) {
          this.setProp('title', value)
        }
      },
      description: {
        get: function () {
          return this.getProp('description')
        },
        set: function (value) {
          this.setProp('description', value)
        }
      },
      price: {
        get: function () {
          return this.contract_draft.milestones[0].milestone_content.price;
        },
        set: function (value) {
          return this.contract_draft.milestones[0].milestone_content.price = value;
        }
      },
      batches() {
        if (this.contract_draft.type === 'single') {
          return this.contract_draft.milestones[0].milestone_content.batches
        }
        return this.contract_draft.batches
      }
    },
    watch: {
      date(val) {
        this.dateFormatted = this.date
      },
      'price': function () {
        this.$store.commit('updateDraftSummary', 'service_cost')
      },
      'contract_draft.type': function () {
        if (this.contract_draft.type === 'several') {
          if (!this.contract_draft.title) {
            this.contract_draft.title = this.contract_draft.milestones[0].milestone_content.title;
          }
          if (!this.contract_draft.description) {
            this.contract_draft.description = this.contract_draft.milestones[0].milestone_content.description;
          }
        }
      },
    },
    methods: {
      setDisabled(value, model) {
        this[model] = value;
      },
      getProp(prop) {
        if (this.contract_draft.type === 'single') {
          return this.contract_draft.milestones[0].milestone_content[prop];
        }
        return this.contract_draft[prop];
      },
      setProp(prop, value) {
        if (this.contract_draft.type === 'single') {
          return this.contract_draft.milestones[0].milestone_content[prop] = value;
        }
        return this.contract_draft[prop] = value;
      },
      datePickerDisabled(value) {
        if (this.readOnly === true) {
          return true
        } else if (this.startDatemenu && this.endDatemenu === false) {
          return true
        } else if (this.endDatemenu && this.startDatemenu === false) {
          return true
        }
        return this[value];
      },
      scrollToBlock(target_block) {
        const container = $('.contract-comp__body'),
          scrollTo = $(target_block);
        if (typeof scrollTo !== 'undefined') {
          container.animate({
            scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop() - 100
          }, 1000);
        }
      },
      changeType(type) {

        if (type === 'several') {
          this.$store.dispatch('calculateSummary');
          const timer = setTimeout(() => {
            this.scrollToBlock('.contact-draft__milestones-list');
            clearTimeout(timer)
          }, 200);
        } else if (type === 'single') {
          this.scrollToBlock('.subject-matter__scroll-to');
        }
        this.contract_draft.type = type;
      },
      createValidateEndDate() {
        let self = this;
        this.$validator.extend('earlier', {
          getMessage(field, val) {
            return 'Start date bigger then end date'
          },
          validate(value, field) {
            if (!value.length || !self.contract_draft.milestones[0].milestone_content.start_date) {
              return false
            }
            let startParts = self.contract_draft.milestones[0].milestone_content.start_date.split('-');
            let endParts = value.split('-');
            let start = new Date(startParts[0], startParts[1] - 1, startParts[2]) // month is 0-based
            let end = new Date(endParts[0], endParts[1] - 1, endParts[2]);

            return end > start
          }
        })
      },
    },
    created() {
      this.createValidateEndDate();
    },
    beforeUpdate() {
      if (this.readOnly) {
        this.textareaDisabledResize(this.getProp('description'))
      }
    },
  }
</script>
