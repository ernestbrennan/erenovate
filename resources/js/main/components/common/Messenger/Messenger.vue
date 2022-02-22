<template>
    <div class="messages-component">
        <div class="chat">

            <slot name="controls"></slot>

            <div class="chat-outer" style="position:relative">

                <div class="chat__body scrollbar"
                     id="scrollBlockId"
                     ref="scrollBlock"

                     :style="{height: container_height,'min-height': container_height}"
                     v-on:scroll="scroll"
                     :class="type">

                    <div class="chat__inner">
                        <slot name="body"></slot>
                    </div>

                </div>

                <div class="new-msg__scroll-down" v-if="show_messenger_scroll_to_bottom">
                    <div class="counter-msg">{{ scroll_message_count}}</div>
                    <v-btn @click="toBottomChat" fab dark small color="primary">
                        <v-icon color="white" size="16px">arrow_downward</v-icon>
                    </v-btn>
                </div>
            </div>

            <messenger-form :type="type"/>

        </div>
    </div>
</template>

<script>
    import MessengerForm from './parts/Form'
    import {mapGetters} from 'vuex'

    export default {
        components: {
            'messenger-form': MessengerForm,
        },
        props: [
            'type'
        ],
        data() {
            return {
                performance: performance.now(),
            }
        },
        computed: {
            ...mapGetters(["user", "chat", "show_messenger_scroll_to_bottom", 'scroll_message_count']),

            container_height() {
                let width = window.innerWidth;
                let height = this.$store.state.containerHeight;
                let val;
                if (width >= 992) {
                    val = height - 155
                    this.$store.commit('set', {type: 'chatBodyHeight', data: val})
                    return height - 155 + 'px';
                } else if (width < 992 && width >= 768) {
                    val = height - 90
                    this.$store.commit('set', {type: 'chatBodyHeight', data: val})
                    return height - 90 + 'px';
                } else {
                    val = height - 80
                    this.$store.commit('set', {type: 'chatBodyHeight', data: val})
                    return height - 80 + 'px';
                }
            },
        },
        methods: {
            toBottomChat() {
                const scroll = $('#scrollBlockId')[0].scrollHeight
                const container = $('#scrollBlockId');
                container.animate({
                    scrollTop: scroll
                }, 1000);

                this.$store.state.show_messenger_scroll_to_bottom = false;
                this.$store.state.scroll_message_count = 0;

            },
            scroll(event) {
                var all_message_loaded = this.chat.total_message_count === this.chat.messages.length;
                let lastcount = this.chat.messages.length - 1,
                    container = $('#scrollBlockId');


                let is_bottom_of_messenger = Math.round(event.target.offsetHeight + event.target.scrollTop) === Math.round(event.target.scrollHeight);

                if (this.show_messenger_scroll_to_bottom && is_bottom_of_messenger) {
                    this.toBottomChat()
                }

                if (this.chat.id && event.target.scrollTop === 0 && !all_message_loaded) {

                    let params = {
                        chat_id: this.chat.id,
                        offset: this.chat.messages.length
                    };

                    this.$store.dispatch('getMessages', params).then(response => {
                        const scroll = $('#scrollBlockId .chat__inner .chat-message').eq(lastcount).offset().top;
                        $('#scrollBlockId').scrollTop(scroll - container.offset().top + container.scrollTop());

                    })

                }
            },

        },
    }
</script>
