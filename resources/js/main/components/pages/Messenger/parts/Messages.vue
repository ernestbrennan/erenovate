<template>
    <messenger-content :type="type">

        <div class="chat__typing-label" :class="{'active': typing}">
            <span class="main-text">{{target_user.firstname}} is Typing
                <span class="dot-1">.</span>
                <span class="dot-2">.</span>
                <span class="dot-3">.</span>
            </span>
        </div>

        <div v-if="!chat.messages.length" class="chat__empty-messeges messenger-ho-9a" :style="{'min-height': this.$store.state.chatBodyHeight + 'px'}">
            <div class="chat__empty-box">
                <img :src="'/img/empty-chat-img.svg'" alt="" class="common-img_m-b-40-35">
                <h4 class="ms-10-class ms-1-class common-h4 text-xs-center msa-7as ms-7bas" data-v-step="ms-7">
                    Welcome to Project Messenger
                </h4>

                <template v-if="user.role === 'homeowner'">
                    <p class="common-p text-xs-center" >
                        Start the conversation with the Bonded Pro you hired. Simply type a message in the
                        field below to start. All messages and key events are tracked here for your
                        convenience. <i>Happy eRenovating</i>!
                    </p>
                </template>

                <template v-else>
                    <p class="common-p text-xs-center">
                        Welcome to Project Messages
                    </p>
                    <p class="common-p text-xs-center">
                        Looks like you haven’t started a conversation with the Client you hired. To
                        start the conversation, type a message in the field below and send. All your
                        messages and key project events are tracked here for your convenience.
                        <i>Happy eRenovating!</i>
                    </p>
                </template>
            </div>
        </div>

        <div class="chat-message messenger-ho-9a msa-7as ms-7bas ms-10-class ms-1-class"
             data-v-step="ms-7"
             v-else

             v-for="message in chat.messages"
             :key="message.id">


            <system-notification :user="user.userId === message.sender_id ? user : chat.target_user"
                                 v-if="message.type === 'system'"
                                 :message="message"
            />

            <message v-else :message="message" :user="user.userId === message.sender_id ? user : chat.target_user"/>

        </div>

        <template v-if="getTour">
            <v-tour name="tourMessenger" :steps="steps" :options="optionsVueTour" :callbacks="messengerCallbacks"></v-tour>
        </template>

    </messenger-content>
</template>

<script>
    import MessengerTour from '../../../../plugins/tour/messanger'
    import tourInit from '../../../../plugins/tour/init'

    import MessengerContent from '../MessengerContent.vue'
    import SystemNotification from '../Types/SystemNotification/SystemNotification'
    import Message from '../Types/Message/Message'

    import {mapGetters} from 'vuex'

    export default {
        mixins:[MessengerTour,tourInit],
        components: {
            'messenger-content': MessengerContent,
            'system-notification': SystemNotification,
            'message': Message
        },
        data() {
            return {
                type: 'message',
                unlockCounter: 0,
                typing: false
            }
        },
        created() {
            this.$store.state.global_loader = true;
            this.$store.dispatch('getChat', this.$route.params.guarantee_project_id).then(response => {
                this.$store.state.global_loader = false;
                this.scrollToBottom();
                this.updateAllSteps();

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
                        console.log(1);
                        let _this = this;

                        this.typing = true;

                        if (e.user_id === this.target_user.userId) {
                            setTimeout(function() {
                                _this.typing = false
                            }, 900);
                        }

                    });
            })

        },
        computed: {
            ...mapGetters(["user", "target_user", "chat", "guarantee_project"]),

            emptyTextChat(){
                if (this.user.role === 'homeowner' ){
                    return 'Looks like you haven’t yet started a conversation with the Verified Pro you hired.\n' +
                        '                    To start messaging, enter your message in the form below.'
                } else {
                    return 'Looks like you haven’t yet started a conversation with your client. To start the discussion, simply enter a message in the form below.'
                }
            },
        },
        methods:{
            scrollToBottom() {
                const timer = setTimeout(()=>{
                    if ($("#scrollBlockId").length > 0){
                        $("#scrollBlockId").scrollTop($("#scrollBlockId")[0].scrollHeight);
                    }
                    clearTimeout(timer);
                },200)
            },
            unlockAudio(){
                if(this.unlockCounter === 1) { return false }
                const audio = new Audio(this.$store.state.chat_audio.currentSrc);
                try {
                    audio.play().catch(e => { return false})
                    audio.pause()
                    audio.currentTime = 0
                } catch {
                }

                this.unlockCounter = 1
            },
        },
        mounted(){
            this.scrollToBottom();
            document.body.addEventListener('touchstart', this.unlockAudio, false)
            document.body.addEventListener('click', this.unlockAudio, false)
        },
        beforeDestroy() {
            document.body.removeEventListener('touchstart', this.unlockAudio, false)
            document.body.removeEventListener('click', this.unlockAudio, false)

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
