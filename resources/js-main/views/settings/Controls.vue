<template>
  <div>
    <div ref="contractHeader" class="contract-comp__header settings__header">
      <div class="g-flex g-flex_row g-flex_wrap margin-10" data-v-step="ccd-3">
        <div class="contract-header__title hidden-sm-and-down" data-v-step="ccd-2">
          YOUR SETTINGS
        </div>
        <v-spacer class="hidden-sm-and-down"></v-spacer>
        <v-spacer class="hidden-md-and-up"></v-spacer>
        <button @click="validateInfo" class="main-btn main-btn_full-green">
          SAVE SETTINGS
        </button>

      </div>
    </div>
  </div>
</template>
<script>
  import {mapGetters} from 'vuex'
  import child_validator from "@/components/mixins/validator/base/child_validator";
  import {saveSettings} from '@/api/setting'

  export default {
    mixins: [child_validator],
    computed: {
      ...mapGetters(['setting']),
    },
    methods: {
      submitSettings() {
        saveSettings(this.setting).then(response => {
          this.$store.commit('set', {
            type: 'errorAlert',
            data: {type: 'info', text: 'Settings saved'}
          });
        })
      },
      validateInfo() {
        let isValid = false;
        this.$validator.validateAll().then((result) => {
          if (result) {
            isValid = true
            this.submitSettings();
          }
          if (!result) {
            isValid = false
          }
        });

        if (!isValid) {
          setTimeout(() => {
            if (!isValid) {
              var container = $('.contract-comp__body'),
                scrollTo = $('.contract-comp__body .scroll__invalid-input').eq(0);
              if (typeof scrollTo !== 'undefined') {
                container.animate({
                  scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop() - 200
                }, 1000);
              }
            }
          }, 500)
        }
      },
    },
  }
</script>
