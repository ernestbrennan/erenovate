<template>
  <div class="contract-draft__gray-box contract-draft__gray-box_min-height contract-draft__personal-data">
    <form>
      <h3 class="contract-draft__box-title">Client Information</h3>

      <div class="contract-draft__input-box">
        <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('invite_first_name') }">
          First Name
        </label>

        <input
          type="text"
          name="invite_first_name"
          class="contract-draft__input"
          placeholder="Example: John"
          v-model="invite_info.first_name"
          key="invite_first_name"
          v-validate="'required'"
          @focus="clearErrors('invite_first_name')"
          :class="{'invalid-input':  errors.has('invite_first_name'), 'scroll__invalid-input':  errors.has('invite_first_name')  }"
        >

        <div v-if="errors.has('invite_first_name')" class="invalid-message">
          {{ errors.first('invite_first_name') }}
        </div>
      </div>
      <div class="contract-draft__input-box">
        <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('invite_last_name') }">
          Last Name
        </label>

        <input
          type="text"
          name="invite_last_name"
          class="contract-draft__input"
          placeholder="Example: Doe"
          v-model="invite_info.last_name"
          key="invite_last_name"
          v-validate="'required'"
          @focus="clearErrors('invite_last_name')"
          :class="{'invalid-input':  errors.has('invite_last_name'), 'scroll__invalid-input':  errors.has('invite_last_name')  }"
        >

        <div v-if="errors.has('invite_last_name')" class="invalid-message">
          {{ errors.first('invite_last_name') }}
        </div>
      </div>

      <div class="contract-draft__input-box">
        <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('invite_email') }">
          Email
        </label>

        <input
          type="text"
          name="invite_email"
          class="contract-draft__input"
          placeholder="Client Email"
          v-model="invite_info.email"
          key="invite_email"
          v-validate="'required|email'"
          @focus="clearErrors('invite_email')"
          :class="{'invalid-input':  errors.has('invite_email'), 'scroll__invalid-input':  errors.has('invite_email')  }"
        >

        <div v-if="errors.has('invite_email')" class="invalid-message">
          {{ errors.first('invite_email') }}
        </div>
      </div>

      <div class="contract-draft__input-box">
        <label class="contract-draft__input-label">
          Address
        </label>

        <input
          type="text"
          name="invite_address"
          class="contract-draft__input"
          placeholder="Example: App. 123"
          v-model="invite_info.address"
        >
      </div>


      <div class="contract-draft__input-box">
        <label class="contract-draft__input-label">
          City
        </label>

        <input
          type="text"
          name="invite_city"
          class="contract-draft__input"
          placeholder="Example: Toronto"
          v-model="invite_info.city"
        >
      </div>

      <div class="contract-draft__input-box">
        <label class="contract-draft__input-label">
          Postal Code
        </label>

        <input
          type="text"
          name="invite_postal_code"
          class="contract-draft__input"
          placeholder="A1A 1A1"
          v-model="invite_info.postal_code"
          v-mask="'A#A #A#'"
        >
      </div>
    </form>
  </div>
</template>
<script>
  import child_validator from "@/components/mixins/validator/base/child_validator";
  import {mapGetters} from 'vuex'

  export default {
    mixins: [child_validator],
    computed: {
      ...mapGetters(["user", "invite_info"]),
    },
  }
</script>
