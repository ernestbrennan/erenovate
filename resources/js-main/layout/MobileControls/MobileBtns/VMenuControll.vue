<template>
  <div class="hidden-md-and-up">
    <v-menu
      offset-y
      max-width="260"
      min-width="240"
      :max-height="maxHeight"
      left
      nudge-top="-12"
      v-model="slideMenu"
    >
      <button
        class="header-controls__chat-dropdown"
        :class="{ active: slideMenu}"
        slot="activator"
      >
        <img :src="iconShowsChat" alt="">
      </button>
      <ul :class="{active: slideMenu}" class="header-controls__chat-option-list">
        <slot></slot>
      </ul>
    </v-menu>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        slideMenu: false,
        maxHeight: window.innerHeight - 57,
      }
    },
    computed: {
      iconShowsChat() {
        if (this.slideMenu === false) {
          return '/img/icon/options_gray.svg'
        } else {
          return '/img/icon/close_white.svg'
        }
      },
    },
    methods: {
      setMaxHeight() {
        this.maxHeight = window.innerHeight - 57;
      },
    },
    mounted() {
      window.addEventListener('resize', this.setMaxHeight)
    },
    beforeDestroy() {
      window.removeEventListener('resize', this.setMaxHeight)
    }
  }
</script>
