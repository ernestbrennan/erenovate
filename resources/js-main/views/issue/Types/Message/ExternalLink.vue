<template>
  <div class="chat-message">
    <div class="chat-message__row">

      <div class="chat-message__text-box">
        <link-prevue :url="urlHost">
          <template slot="loading">
            <!-- set your custom loading -->
            <v-progress-circular
              indeterminate
              color="primary"
            ></v-progress-circular>
          </template>
          <template slot-scope="props">
            <div class="link-preview">
              <div class="link-preview__img-box">
                <img class="img-fluid link-preview__img" :src="props.img" :alt="props.title">
              </div>
              <div class="link-preview__text-box">
                <h4 class="link-preview__title">{{props.title | short}}</h4>
                <h5 class="link-preview__link">{{url}}</h5>
                <p class="link-preview__desc">{{props.description | short}}</p>
              </div>
            </div>
          </template>
        </link-prevue>
      </div>
    </div>
  </div>
</template>
<script>
  import LinkPrevue from 'link-prevue'

  export default {
    components: {
      'link-prevue': LinkPrevue
    },
    filters: {
      short: function (text) {
        return text.slice(0, 120) + (120 < text.length ? '...' : '')
      }
    },
    props: [
      'url',
      'user',
      'urlHost'
    ],
    mounted() {
    },
  }
</script>
