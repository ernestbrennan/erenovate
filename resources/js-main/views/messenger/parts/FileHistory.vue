<template>
  <div class="messages-component">
    <div class="chat">
      <div class="chat__header hidden-sm-and-down fds" data-v-step="fh-4">
        <v-layout row wrap align-center>
          <h4 class="chat__header-title" data-v-step="fh-1">
            <span>Files History</span>
          </h4>
          <v-spacer></v-spacer>
          <button class="main-btn main-btn_full-blue" @click="toMessages" data-v-step="fh-5">
            MESSAGES
          </button>
        </v-layout>
      </div>

      <div :style="{height: component_container_height}" class="chat__body scrollbar" ref="scrollBlock">
        <div class="chat__inner">
          <div v-if="!fileHistory.entities.length" class="chat__empty-messeges"
               :style="{'min-height': this.$store.state.chatBodyHeight + 'px'}">
            <div class="chat__empty-box">
              <img :src="'/img/no-files-history.svg'" alt="">
              <h4 class="common-h4 text-xs-center" data-v-step="fh-2">
                Upload Your First File
              </h4>
              <p class="common-p text-xs-center" data-v-step="fh-3">
                No files attached to this Project.
              </p>

              <template v-if="user === 'homeowner'">
                <p class="common-p text-xs-center">
                  When you attach files (upload) to platform messages, projects, milestones or payment details, you can
                  easily view them on this File History area.
                </p>
                <p class="common-p text-xs-center">
                  Your Private notes attachments do not appear here.
                </p>
              </template>
              <template v-else>
                <p class="common-p text-xs-center">
                  Any project files you upload to the platform, or messages, projects, milestones or payment details,
                  you can easily view them on this File History area.
                </p>
                <p class="common-p text-xs-center">
                  NB - Your Private notes do not appear here.
                </p>
              </template>

            </div>
          </div>

          <div v-else v-for="entity in fileHistory.entities" data-v-step="fh-2" class="chat-message">

            <message
              :message="entity"
              data-v-step="fh-3"
              :user="user.userId === entity.sender_id ? user : fileHistory.target_user"
            />
          </div>
        </div>
      </div>
    </div>
    <template v-if="getTour">
      <v-tour name="tourFiles" :steps="steps" :options="optionsVueTour" :callbacks="tourCallbacks"></v-tour>
    </template>
  </div>

</template>

<script>
  import FileHTour from '@/plugins/tour/fileHistory'
  import tourInit from '@/plugins/tour/init'

  import Message from '../Types/Message/Message'
  import {mapGetters} from 'vuex'

  export default {
    mixins: [FileHTour, tourInit],
    components: {
      'message': Message,
    },
    created() {
      this.$store.state.global_loader = true;

      this.$store.dispatch('getFileHistory', this.$route.params.guarantee_project_id).then(response => {
        this.scrollToBottom();
        this.$store.state.global_loader = false
      })
    },
    computed: {
      ...mapGetters(["user", "fileHistory"]),
      component_container_height() {

        let width = window.innerWidth;
        let height = this.$store.state.containerHeight;
        let val;

        if (width >= 992) {
          val = height - 65
          this.$store.commit('set', {type: 'chatBodyHeight', data: val})
          return height - 65 + 'px'
        } else if (width < 992 && width >= 768) {
          val = height
          this.$store.commit('set', {type: 'chatBodyHeight', data: val})
          return height + 'px'
        } else {
          val = height
          this.$store.commit('set', {type: 'chatBodyHeight', data: val})
          return height + 'px'
        }

      },
    },

    methods: {
      toMessages() {
        this.$router.push({
          name: 'messages',
          params: {guarantee_project_id: this.$route.params.guarantee_project_id}
        })
      },
      scrollToBottom() {
        const timer = setTimeout(() => {
          const box = this.$refs.scrollBlock;
          $(box).scrollTop($(box)[0].scrollHeight);
          clearTimeout(timer);
        }, 200)
      },
    },
    mounted() {
      this.scrollToBottom();
    },

  }
</script>
