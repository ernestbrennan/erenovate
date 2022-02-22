<template>
  <div class="contract-draft__gray-box contract-draft__gray-box_min-height contract-draft__personal-data">

    <form>
      <h3 class="contract-draft__box-title">Your Contact Information</h3>
      <div class="toggle-buttons" v-if="!canCurrentEdit && user.role === 'homeowner'">
        <button
          class="toggle-buttons__el left"
          :class="{active: current.type === 'individual'}"
          :disabled="canCurrentEdit"
          @click="changeType('individual')"
        >
          INDIVIDUAL
        </button>
        <button
          class="toggle-buttons__el right"
          :class="{active: current.type === 'legal'}"
          :disabled="canCurrentEdit"
          @click="changeType('legal')"
        >
          LEGAL ENTITY
        </button>
      </div>
      <template v-if="current.type === 'individual'">
        <div class="contract-draft__input-box">
          <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('first name')}">
            First Name
          </label>
          <input
            type="text"
            class="contract-draft__input"
            placeholder="Example: John"
            v-model="current.first_name"
            :disabled="canCurrentEdit"
            v-validate="'required'"
            @focus="clearErrors('first name')"
            name="first name"
            key="first name"
            :class="{'invalid-input':  errors.has('first name'),'scroll__invalid-input':  errors.has('first name') }"
          >
          <div v-if="errors.has('first name')" class="invalid-message">
            {{ errors.first('first name') }}
          </div>
        </div>
        <div class="contract-draft__input-box">
          <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('last name') }">
            Last Name
          </label>
          <input
            type="text"
            name="last name"
            class="contract-draft__input"
            placeholder="Example: Jones"
            v-model="current.last_name"
            :disabled="canCurrentEdit"
            key="last_name"
            @focus="clearErrors('last name')"
            v-validate="'required'"
            :class="{'invalid-input':  errors.has('last name'), 'scroll__invalid-input':  errors.has('last name')  }"

          >
          <div v-if="errors.has('last name')" class="invalid-message">
            {{ errors.first('last name') }}
          </div>
        </div>
      </template>
      <template v-if="current.type === 'legal'">

        <div class="contract-draft__input-box">
          <label :class="{'invalid-input':  errors.has('company name') }" class="contract-draft__input-label">
            Company Name
          </label>
          <input
            type="text"
            name="company name"
            class="contract-draft__input"
            placeholder="Example: Erenovate LLC"
            v-model="current.company_name"
            key="company_name"
            v-validate="'required'"
            @focus="clearErrors('company name')"
            :class="{'invalid-input':  errors.has('company name'), 'scroll__invalid-input':  errors.has('company name')  }"
            :disabled="canCurrentEdit"
          >
          <div v-if="errors.has('company name')" class="invalid-message">
            {{ errors.first('company name') }}
          </div>
        </div>
        <div class="contract-draft__input-box">
          <label :class="{'invalid-input':  errors.has('representative') }" class="contract-draft__input-label">
            Name of Representative
          </label>
          <input
            type="text"
            name="representative"
            class="contract-draft__input"
            placeholder="Example: John Doe"
            v-model="current.representative_name"
            :disabled="canCurrentEdit"
            key="representative"
            @focus="clearErrors('representative')"
            v-validate="'required'"
            :class="{'invalid-input':  errors.has('representative'), 'scroll__invalid-input':  errors.has('representative')  }"
          >
          <div v-if="errors.has('representative')" class="invalid-message">
            {{ errors.first('representative') }}
          </div>

        </div>
        <div class="contract-draft__input-box g-flex g-flex_row g-flex_wrap" v-if="!canCurrentEdit">
          <label class="contact-draft__checkbox" for="representative-checkbox-id">
            <input
              type="checkbox"
              id="representative-checkbox-id"
              v-model="company_checkbox"
              class="d-none"
              name="company_checkbox"
              key="company_checkbox"
              @focus="clearErrors('company_checkbox')"
              :disabled="canCurrentEdit"
              v-validate="'required'"
            >
            <span
              :class="{'invalid-input':  errors.has('company_checkbox'), 'scroll__invalid-input':  errors.has('company_checkbox') }"></span>
          </label>
          <label class="contract-draft__checkbox-label" for="representative-checkbox-id">
            I confirm that I am authorized to bind the corporation into this agreement
          </label>
        </div>
      </template>

      <div class="contract-draft__sep-title">Address</div>
      <div class="contract-draft__input-box">
        <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('address 1') }">Address Line
          1
        </label>
        <input
          type="text"
          name="address 1"
          class="contract-draft__input"
          placeholder="Example: 1 Main St. SE"
          v-model="current.address_1"
          :disabled="canCurrentEdit"
          @focus="clearErrors('address 1')"
          v-validate="'required'"
          :class="{'invalid-input':  errors.has('address 1'), 'scroll__invalid-input':  errors.has('address 1') }"
        >
        <div v-if="errors.has('address 1')" class="invalid-message">
          {{ errors.first('address 1') }}
        </div>
      </div>
      <div class="contract-draft__input-box">
        <label :class="{'invalid-input':  errors.has('address 2')}" class="contract-draft__input-label">
          Address Line 2
        </label>
        <input
          type="text"
          name="address 2"
          key="address 2"
          class="contract-draft__input"
          placeholder="Example: App. 123"
          v-model="current.address_2"
          :disabled="canCurrentEdit"
        >
      </div>
      <div class="contract-draft__input-box">
        <label :class="{'invalid-input':  errors.has('city')}" class="contract-draft__input-label">
          City
        </label>
        <input
          type="text"
          name="city"
          key="city"
          class="contract-draft__input"
          placeholder="Example: Toronto"
          v-model="current.city"
          @focus="clearErrors('city')"
          :disabled="canCurrentEdit"
          v-validate="'required'"
          :class="{'invalid-input':  errors.has('city'), 'scroll__invalid-input':  errors.has('city') }"
        >
        <div v-if="errors.has('city')" class="invalid-message">
          {{ errors.first('city') }}
        </div>
      </div>
      <div class="contract-draft__input-box">
        <label :class="{'invalid-input':  errors.has('province')}" class="contract-draft__input-label">
          Province
        </label>
        <input
          type="text"
          name="province"
          key="province"
          class="contract-draft__input"
          placeholder="Example: Ontario"
          v-model="current.province"
          :disabled="canCurrentEdit"
          v-validate="'required'"
          @focus="clearErrors('province')"
          :class="{'invalid-input':  errors.has('province'), 'scroll__invalid-input':  errors.has('province') }"
        >
        <div v-if="errors.has('province')" class="invalid-message">
          {{ errors.first('province') }}
        </div>
      </div>
      <div class="contract-draft__input-box">
        <label :class="{'invalid-input':  errors.has('postal code')}" class="contract-draft__input-label">
          Postal Code
        </label>
        <input
          type="text"
          name="postal code"
          key="postal_code"
          class="contract-draft__input"
          placeholder="A1A 1A1"
          v-model="current.postal_code"
          :disabled="canCurrentEdit"
          v-mask="'A#A #A#'"
          @focus="clearErrors('postal code')"
          v-validate="'required|postal_code'"
          :class="{'invalid-input':  errors.has('postal code'), 'scroll__invalid-input':  errors.has('postal code') }"
        >
        <div v-if="errors.has('postal code')" class="invalid-message">
          {{ errors.first('postal code') }}
        </div>
      </div>
    </form>
    <template v-if="this.current.status !== 'new' && contract_status !== 'signed' && contract_status !== 'finished'">
      <div class="editing-btn-row_center">
        <v-tooltip top v-if="!current_edit">
          <button
            class="main-btn main-btn_border-blue"
            v-if="!current_edit"
            slot="activator"
            @click="startEdit"
          >
            Edit my personal info
          </button>
          <span>{{changeUserInfoTooltip}}</span>
        </v-tooltip>
      </div>

      <div class="editing-btn-row" v-if="current_edit">
        <button class="main-btn main-btn_border-red" @click="cancelEditing">
          CANCEL
        </button>
        <v-spacer></v-spacer>
        <button class="main-btn main-btn_full-blue" @click="applyEditing">
          APPLY
        </button>
      </div>
    </template>
  </div>

</template>
<script>
  import child_validator from "@/components/mixins/validator/base/child_validator";
  import {mapGetters} from 'vuex'
  import {getDraftInfoUser} from "@/api/user";

  export default {
    mixins: [child_validator],
    data() {
      return {
        company_checkbox: '',
        current_edit: false
      }
    },
    props: [
      'current',
      'contract_status',
    ],
    computed: {
      ...mapGetters(["user"]),

      canCurrentEdit() {
        return !(this.current.status === 'new' || this.current_edit);
      },
      changeUserInfoTooltip() {
        const usr_str = this.user.role === 'homeowner' ? 'Pro' : 'Ho';
        return `If you edit your details, ${usr_str} will have to approve the change of details`
      },
    },
    methods: {
      changeType(type) {
        this.current.type = type;
      },
      startEdit() {
        $('.v-tooltip__content.menuable__content__active').css('display', 'none');
        this.current_edit = true;
      },
      cancelEditing() {
        this.clearErrors();
        // api.post('user.getUserInfo', {id: this.current.id})
        getDraftInfoUser(this.current.id).then(response => {
            this.$store.commit('updateContractProp', {prop: 'current_user_info', value: response.data.response});
            this.current_edit = false
          })
      },
      applyEditing() {
        const timer = setTimeout(() => {
          if ($('.contract-draft__personal-data .invalid-input').length <= 0) {
            this.$store.dispatch('updateUserInfo', this.current).then(_ => {
              this.current_edit = false
            });
            clearTimeout(timer)

          } else {
            clearTimeout(timer);
            setTimeout(() => {
              var container = $('.contract-comp__body'),
                scrollTo = $('.contract-draft__personal-data .invalid-input').eq(0);
              if (typeof scrollTo !== 'undefined') {
                container.animate({
                  scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop() - 200
                }, 1000);
              }
            }, 100)
          }
        }, 100);
      },
    },
  }
</script>
