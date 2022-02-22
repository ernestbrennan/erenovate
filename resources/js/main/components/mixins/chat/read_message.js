export default {
    created(){
        if (!this.message.receiver_seen && this.message.sender_id !== this.$store.state.user.userId && this.$route.name !== 'notes') {

            setTimeout(() => {

                api
                    .post('message.readMessage', {
                        id: this.message.id
                    })
                    .then(response => {

                        this.message.receiver_seen = 1;

                        if (this.$store.state.chat.unread_messages_count) {
                            this.$store.state.chat.unread_messages_count--
                        }
                    });
            }, 1500)
        }

    },

};
