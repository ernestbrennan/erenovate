<template>
  <messenger-content :type="type">

    <div class="chat__typing-label" :class="{'active': typing}">
    <span class="main-text">
      {{target_user.firstname}} is Typing
      <span class="dot-1">.</span>
      <span class="dot-2">.</span>
      <span class="dot-3">.</span>
    </span>
    </div>

    <div
      v-if="!chat.messages.length"
      class="chat__empty-messeges messenger-ho-9a"
      :style="{'min-height': this.$store.state.chatBodyHeight + 'px'}"
    >
      <div class="chat__empty-box">
        <img :src="'/img/empty-chat-img.svg'" alt="" class="common-img_m-b-40-35">
        <h4 class="common-h4 text-xs-center " data-v-step="ms-7">
          Start the Discussion
        </h4>
        <p class="common-p text-xs-center">
          {{emptyTextChat}}
        </p>
      </div>
    </div>
    <div
      v-else
      :key="message.id"
      v-for="message in chat.messages"
      data-v-step="ms-7"
      class="chat-message messenger-ho-9a issue-tour-2"
    >

      <admin-message
        v-if="message.type === 'admin'"
        :message="message"
        :user="admin"
      />
      <system-notification
        v-else-if="message.type === 'system'"
        :message="message"
        :user="user.userId === message.sender_id ? user : chat.target_user"
      />

      <message
        v-else
        :message="message"
        :user="user.userId === message.sender_id ? user : chat.target_user"
      />

    </div>
    <template v-if="getTour">
      <v-tour name="tourIssue" :steps="steps" :options="optionsVueTour" :callbacks="tourCallbacks"></v-tour>
    </template>

  </messenger-content>
</template>
<script>
  import MessengerContent from './MessengerContent.vue'
  import SystemNotification from './Types/SystemNotification/SystemNotification'
  import Message from './Types/Message/Message'
  import AdminMessage from './Types/Message/AdminMessage'

  import {mapGetters} from 'vuex'
  import IssueTour from '@/plugins/tour/issue'
  import InitTour from '@/plugins/tour/init'

  export default {
    mixins: [IssueTour, InitTour],
    components: {
      'messenger-content': MessengerContent,
      'system-notification': SystemNotification,
      'message': Message,
      'admin-message': AdminMessage

    },
    data() {
      return {
        type: 'message',
        typing: false,
        admin: {
          firstname: 'Admin',
          role: 'admin',
          photo: '/img/default_avatar.png',
        }
      }
    },
    created() {
      this.$store.state.global_loader = true;

      this.$store.dispatch('getIssue', this.$route.params.issue_id).then(response => {
        this.$store.state.global_loader = false;
        this.scrollToBottom();

        Echo.channel('Chat.' + this.chat.id)

          .listen('.new_message', (event) => {
            this.$store.dispatch('receiveMessage', event);
          })
          .listen('.read_message', (event) => {
            this.$store.dispatch('readMessage', event);
          });

        Echo.join('Chat.Presence.' + this.chat.id)
          .here((event) => {

            if (event.some(user => {
              return user.id === this.target_user.userId;
            })) {
              this.$store.dispatch('joiningChat');
            }
          })
          .joining((event) => {
            this.$store.dispatch('joiningChat');
          })
          .leaving((event) => {
            this.$store.dispatch('leavingChat');
          })
          .listenForWhisper('typing', (e) => {
            let _this = this;

            this.typing = true;

            if (e.user_id === this.target_user.userId) {
              setTimeout(function () {
                _this.typing = false
              }, 900);
            }

          });
      })

    },
    computed: {
      emptyTextChat() {
        if (this.user.role === 'homeowner') {
          return 'Looks like you haven’t yet started a conversation with the Verified Pro you hired.\n' +
            '                    To start messaging, enter your message in the form below.'
        } else {
          return 'Looks like you haven’t yet started a conversation with your client. To start the discussion, simply enter a message in the form below.'
        }
      },
      ...mapGetters(["user", "chat", "guarantee_project", "target_user"])
    },
    methods: {
      scrollToBottom() {
        const timer = setTimeout(() => {
          if ($("#scrollBlockId").length > 0) {
            $("#scrollBlockId").scrollTop($("#scrollBlockId")[0].scrollHeight);
          }
          clearTimeout(timer);
        }, 200)
      },
    },
    mounted() {
      this.scrollToBottom();
    },
    beforeUpdate() {
    },
    beforeDestroy() {
      Echo.leave('Chat.' + this.chat.id);

      this.$store.commit('set', {
        type: 'chat',
        data: {
          messages: [],
          target_user: {},
          guarantee_project: {}
        }
      });
    },
  }
</script>
