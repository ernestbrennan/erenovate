<template>
    <div class="messages-component">
        <div class="chat">
            <messenger-control-head :guarantee_project="guarantee_project" />

            <div :style="{height: windowHeighth,'min-height':windowHeighth}"
                 id="scrollBlockId"
                 class="chat__body scrollbar"
                 ref="scrollBlock"
                 v-on:scroll="getMessages">
                <div class="chat__inner">
                    <slot></slot>
                </div>
            </div>

            <messenger-form :issue="issue"/>
        </div>
    </div>
</template>

<script>
    import MessengerForm from './MessengerForm'
    import MessengerControlHead from './MessengerControlHead';
    import {mapGetters} from 'vuex'

    export default {
        components: {
            'messenger-form': MessengerForm,
            'messenger-control-head': MessengerControlHead
        },
        props: [
            'type'
        ],
        data() {
            return {
                windowHeighth: false,
            }
        },
        computed: {
            ...mapGetters(["user", "chat", "guarantee_project", "issue"]),
        },
        methods: {
            containerHeigth() {
                let width = window.innerWidth;
                let height = this.$store.state.containerHeight;
                let val;
                if (width >= 992) {
                    this.windowHeighth = height - 155 + 'px';
                    val = height - 155
                } else if (width < 992 && width >= 768) {
                    this.windowHeighth = height - 90 + 'px';
                    val = height - 90
                } else {
                    this.windowHeighth = height - 80 + 'px';
                    val = height - 80
                }
                this.$store.commit('set', {type: 'chatBodyHeight', data: val})
            },

            getMessages() {
                return false;
                var all_message_loaded = this.chat.total_message_count === this.chat.messages.length;
                let lastcount = this.chat.messages.length - 1,
                    container = $('#scrollBlockId');

                if (event.target.scrollTop === 0 && !all_message_loaded) {

                    let params = {
                        chat_id: this.chat.id,
                        offset: this.chat.messages.length
                    };

                    this.$store.dispatch('getMessages', params).then(response => {
                        const scroll = $('#scrollBlockId .chat__inner .chat-message').eq(lastcount).offset().top;
                        console.log(scroll,scroll - container.offset().top + container.scrollTop());
                        $('#scrollBlockId').scrollTop(scroll - container.offset().top + container.scrollTop());

                    })

                }
            },

        },
        created() {
            this.containerHeigth();
            window.addEventListener('resize', this.containerHeigth);
        },
    }
</script>
<style>
    .chat__body {
        display: flex;
        flex-direction: column-reverse;
    }
</style>