<template>
  <div class="dz-preview dz-processing dz-image-preview dz-success dz-complete">
    <div class="dz-inner-box">
      <div class="dz-info">
        <div class="dz-filename">
          <div class="dz-progressbar__box">
            <img src="/img/icon/one-file_gray.svg" alt="file">
          </div>
          <span class="dz-filename__text">{{ file.name|substring_35}}</span></div>
        <div class="dz-size">
          <span v-html="formatSize(file.size)"></span>
        </div>
      </div>
      <div class="dz-option-box">
        <template v-if="loading && is_mobile">
          <v-progress-circular indeterminate color="primary"></v-progress-circular>
        </template>
        <template v-else>
          <img
            class="dz-upload-target"
            src="/img/icon/upload_gray.svg"
            alt="Click me to download"
            style="cursor:pointer"
            @click="downloadWithLoading(file.id)"
          >
        </template>
      </div>
    </div>
  </div>
</template>

<script>

  import dropzone from '@/components/mixins/dropzone/dropzone.js'

  export default {
    mixins: [dropzone],
    data() {
      return {
        loading: false
      }
    },
    props: {
      file: Object
    },
    computed: {
      is_mobile() {
        if (window.innerWidth >= 768) {
          return false
        }
        return true
      },

    },

  }
</script>

