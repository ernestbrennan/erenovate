<template>
  <div v-if="error">
    <v-snackbar
      multi-line
      :timeout="4000"
      :color="error.type"
      @input="closeError"
      :value="true"
      :top="true"
    >
      {{ error.text }}
      <v-btn
        color="white"
        flat
        @click="closeError"
      >
        Close
      </v-btn>
    </v-snackbar>
  </div>
</template>

<script>
  export default {
    computed: {
      error() {
        return this.$store.getters.errorAlert
      }
    },
    methods: {
      closeError() {
        this.$store.dispatch('set', {type: 'errorAlert', data: null})
      },
    },
  }
</script>
