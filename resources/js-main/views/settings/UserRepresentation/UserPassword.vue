<template>
  <div class="settings__change-pass-wrapper">
    <div class="contract-draft__input-box">

      <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('current_pass')}">
        Current Password <span class="gray-text">(Enter New Password if you wish to change)</span>
      </label>
      <input
        :class="{'invalid-input':  errors.has('current_pass'),'scroll__invalid-input':  errors.has('current pass') }"
        class="contract-draft__input"
        placeholder="Current Password"
        v-validate="'required|min:5'"
        data-vv-as="current password"
        data-vv-name="current_pass"
        type="password"
        v-model="current_pass"
      >
      <div v-if="errors.has('current_pass')" class="invalid-message">
        {{ errors.first('current_pass') }}
      </div>

    </div>
    <div class="contract-draft__input-box">
      <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('new_pass')}">
        New Password
      </label>
      <input
        :class="{'invalid-input':  errors.has('new_pass'),'scroll__invalid-input':  errors.has('new_pass') }"
        class="contract-draft__input"
        placeholder="New Password"
        ref="password"
        v-validate="'required|min:5'"
        data-vv-as="new pass"
        data-vv-name="new_pass"
        type="password"
        v-model="new_pass"
      >
      <div v-if="errors.has('new_pass')" class="invalid-message">
        {{ errors.first('new_pass') }}
      </div>

    </div>
    <div class="contract-draft__input-box">

      <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('confirm_new_pass')}">
        Confirm new pass.
      </label>
      <input
        :class="{'invalid-input':  errors.has('confirm_new_pass'),'scroll__invalid-input':  errors.has('confirm_new_pass') }"
        class="contract-draft__input"
        placeholder="Confirm new pass"
        v-validate="'required|confirmed:password|min:5'"
        data-vv-as="confirm"
        data-vv-name="confirm_new_pass"
        type="password"
        v-model="confirm_new_pass"
      >
      <div v-if="errors.has('confirm_new_pass')" class="invalid-message">
        {{ errors.first('confirm_new_pass') }}
      </div>

    </div>
    <div class="settings__pass-submit-box">
      <button @click="validate" class="main-btn main-btn_full-green settings__btn-change-pass">
        <template v-if="loading">
          <v-progress-circular indeterminate color="white"/>
        </template>
        <template v-else>
          Update password
        </template>
      </button>
    </div>
  </div>
</template>
<script>
  import {changePasswordSettings} from '@/api/setting'

  export default {
    name: "UserPassword",
    data() {
      return {
        loading: false,
        current_pass: '',
        new_pass: '',
        confirm_new_pass: '',
      }
    },
    methods: {
      validate() {
        let isValid = false;
        this.$validator.validateAll().then((result) => {
          if (result) {
            isValid = true
          } else {
            isValid = false
          }

          if (!isValid) {
            this.$store.commit('set', {
              type: 'errorAlert',
              data: {type: 'error', text: 'Validate false'}
            });
            this.loading = false;
          } else if (isValid && this.leading) {
            return false
          } else if (isValid && this.loading === false) {
            this.loading = true;
            this.saveNewPass();
          }
        });
      },
      saveNewPass() {

        changePasswordSettings(this.current_pass, this.new_pass).then(response => {

          this.$store.commit('set', {
            type: 'errorAlert',
            data: {type: 'info', text: 'Password saved'}
          });

        })
          .catch(error => {
            this.$store.commit('set', {
              type: 'errorAlert',
              data: {
                type: 'error',
                text: error.response.data.message.current_password[0]
              }
            });
          });

        this.loading = false;
      },
    },
  }
</script>
