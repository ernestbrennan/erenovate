<template>
    <messenger-content :type="type">

        <div v-if="!chat.messages.length" class="chat__empty-messeges messenger-ho-9a" :style="{'min-height': this.$store.state.chatBodyHeight + 'px'}">
            <div class="chat__empty-box">
                <img :src="'/img/empty-chat-img.svg'" alt="" class="common-img_m-b-40-35">
                <h4 class="common-h4 text-xs-center ">
                    Start the Discussion
                </h4>
                <p class="common-p text-xs-center">
                    Looks like you haven’t yet started a conversation with your client. To start the discussion, simply enter a message in the form below.
                </p>
            </div>
        </div>
        <div v-else
             :key="message.id"
             v-for="message in chat.messages"
             class="chat-message messenger-ho-9a">

            <admin-message v-if="message.type === 'admin'"
                           :message="message"
                           :user="user"
            />

            <system-notification v-else-if="message.type === 'system'"
                                 :message="message"
                                 :user="chat.homeowner.userId === message.sender_id ? chat.homeowner : chat.contractor"
            />

            <message v-else
                     :message="message"
                     :user="chat.homeowner.userId === message.sender_id ? chat.homeowner : chat.contractor"
            />

        </div>

    </messenger-content>
</template>
<script>

    import MessengerContent from './MessengerContent'
    import SystemNotification from './Types/SystemNotification/SystemNotification'
    import Message from './Types/Message/Message'
    import AdminMessage from './Types/Message/AdminMessage'

    import {mapGetters} from 'vuex'

    export default {
        components: {
            'messenger-content': MessengerContent,
            'system-notification': SystemNotification,
            'message': Message,
            'admin-message': AdminMessage
        },
        data() {
            return {
                type: 'message'
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
                    });
            })

        },
        computed: {
            ...mapGetters(["user", "chat", "guarantee_project"])
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
        },
        mounted(){
            this.scrollToBottom();
        },
        beforeUpdate(){
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
<style>
</style>