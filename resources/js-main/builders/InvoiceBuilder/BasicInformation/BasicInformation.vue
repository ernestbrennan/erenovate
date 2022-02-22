<template>
  <div class="contract-draft__el-box">
    <div class="contract-draft__title-box" @click="toggleBlock">
      <div class="contract-draft__title">Payment Request Details</div>
      <img :src="'/img/icon/caret-icon_gray.svg'" :class="{ active: iconToggle }" class="contract-draft__curret">
    </div>

    <div
      ref="arguments_box"
      class="contract-draft__slide-main-box contract-draft__gray-box_invoice contract-draft__gray-box"
    >
      <div class="contract-draft__input-box">
        <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('invoice title') }">
          Payment request title
        </label>
        <input
          placeholder="Enter Payment request title"
          type="text"
          class="contract-draft__input"
          key="contract_title"
          v-model="invoice.title"
          data-vv-name="invoice title"
          v-validate="'required'"
          @focus="clearErrors('invoice title')"
          :class="{'invalid-input':  errors.has('invoice title') , 'scroll__invalid-input':  errors.has('invoice title')}"
          :disabled="readOnly"
        >

        <div v-if="errors.has('invoice title')" class="invalid-message">
          The payment request field is required.
        </div>
      </div>
      <div class="contract-draft__input-box">
        <label data-v-step="ci-3" class="contract-draft__input-label ciho-2">
          Payment request number (optional)
        </label>
        <input
          placeholder="Enter payment request number"
          type="text"
          class="contract-draft__input"
          key="contract_title"
          v-model="invoice.number"
          :disabled="readOnly"
        >
      </div>
      <div class="contract-draft__input-box">
        <label class="contract-draft__input-label">
          Payment request description (optional)
        </label>
        <textarea
          ref="textareaDesc"
          placeholder="Enter payment request description"
          class="contract-draft__textarea"
          name="contract description"
          key="contract_description"
          v-model="invoice.description"
          :disabled="readOnly"
        >
        </textarea>
      </div>
      <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
          <div class="contract-draft__input-box">
            <label class="ci-4 contract-draft__input-label" :class="{'invalid-input':  errors.has('creation date') }">
              Created On
            </label>
            <input-date
              v-model="invoice.creation_date"
              :isValidate="true"
              :readOnly="readOnly"
              label="creation date"
              v-validate="'required'"
              @datePicked="repeatToDuodate"
              @focus="clearErrors('creation date')"
              data-vv-as="creation date"
              :error="errors.first('creation date')"
              ref="startDate"
            />
          </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
          <div class="contract-draft__input-box">
            <label slot="activator" class="contract-draft__input-label">Due Date</label>
            <input-date v-model="invoice.due_date" :isValidate="false" :readOnly="readOnly"/>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import {nextTickResize, resizeTextareaReadOnly} from "@/components/common/Mixins/textarea"
  import child_validator from "@/components/mixins/validator/base/child_validator"

  import DatePicker from '@/components/common/elements/DatePicker'

  export default {
    components: {
      'input-date': DatePicker,
    },
    mixins: [child_validator, nextTickResize, resizeTextareaReadOnly],
    data() {
      return {
        iconToggle: true,
        creationToggle: false,
        duoToggle: false,
      }
    },
    props: {
      invoice: Object,
      readOnly: Boolean
    },
    methods: {
      toggleBlock() {
        this.iconToggle = !this.iconToggle
        $(this.$refs.arguments_box).slideToggle();
      },
      repeatToDuodate(value) {
        if (this.invoice.due_date === null) {
          this.invoice.due_date = value
        }
      },
    },
    beforeUpdate() {
      if (this.readOnly) {
        this.textareaDisabledResize(this.invoice.description)
      }
    },
  }
</script>
