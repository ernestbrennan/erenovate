<template>
  <div>
    <div class="chat-message__row chat-message__system">

      <div class="chat-message__avatar">
        <img class="human admin-icon" src="/img/support-avatar.svg" alt="avatar" @click="toProfile">
      </div>

      <div class="chat-message__text-box">

        <h5><span class="admin-label">{{ user.firstname }}</span> , {{ message.created_at | moment("MMM D h:mm:ss A")}}
        </h5>

        <div class="table-data__wr">
          <div :key="index" v-for="(file, index) in message.files" class="table-files">
            <div class="table-files__name-size-row">
              <div class="table-files__icon-name">
                <div class="img-position"><img :src="'/img/icon/chat-file.svg'" alt=""></div>
                {{ file.name }}
              </div>
              <div class="table-files__sizes">
                <span v-html="formatSize(file.size)"></span>
              </div>
            </div>
            <div class="table-files__link">
              <a @click.prevent="download(file.id)" v-html="downloadLink"></a>
            </div>
          </div>
          <p v-html="messageText"></p>
        </div>

      </div>
    </div>
    <template v-for="url in urls">
      <external-link :urlHost="extractHostname(url)" :user="user" :url="url"></external-link>
    </template>
  </div>
</template>
<script>
  import ExternalLink from './ExternalLink';
  import dropzone from '@/components/mixins/dropzone/dropzone';

  export default {
    mixins: [dropzone],
    components: {
      'external-link': ExternalLink
    },
    data() {
      return {
        urls: this.message.content.match(this.getRegex()),
      }
    },
    props: [
      'message',
      'user'
    ],
    computed: {
      downloadLink() {
        if (window.innerWidth >= 768) {
          return 'DOWNLOAD'
        }
        return `<img src="/img/icon/download-mobile-chat_blue.svg" alt="">`
      },
      messageText() {
        return this.message.content.replace(this.getRegex(), function (url) {
          return '<a target="_blank" href="' + url + '">' + url + '</a>';
        });
      }
    },
    methods: {
      extractHostname(url) {
        let hostname, site;
        const http = url.split('://')[0];
        if (url.indexOf("//") > -1) {
          hostname = url.split('/')[2];
        } else {
          hostname = url.split('/')[0];
        }
        hostname = hostname.split(':')[0];
        hostname = hostname.split('?')[0];
        site = http + '://' + hostname;
        return site;
      },
      getRegex() {
        return new RegExp("https?:\\/\\/(www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{2,256}\\.[a-z]{2,6}\\b([-a-zA-Z0-9@:%_\\+.~#?&//=]*)", "g");
      },
      toProfile() {
        if (this.user.role === 'admin') {
          return false
        }
        window.open(
          this.user.profile_link,
          '_blank'
        );
      }
    }
  }
</script>


