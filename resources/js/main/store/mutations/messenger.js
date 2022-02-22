let isAudioPlay = false;
export default {
    addMessage(state, message) {

        var message_exists = state.chat.messages.find(function (item) {
            return item.id === message.id;
        });

        if (!message_exists) {

            state.chat.total_message_count += 1;
            state.chat.messages.unshift(message);

            if (message.notification && message.notification.guarantee_project){
                state.guarantee_project = message.notification.guarantee_project
            }

            if (message.sender_id !== state.user.userId) {

                state.show_messenger_scroll_to_bottom = true;
                state.scroll_message_count++;

                if(state.chat_audio !== null || isAudioPlay === false){
                    isAudioPlay = true;
                    state.chat_audio.play()
                    const timer = setTimeout(()=>{
                        isAudioPlay = false
                        clearTimeout(timer)
                    }, parseInt(state.chat_audio.duration + 20))
                }
            }
        }

        var contract = state.contracts.find(function (item) {
            return item.chat.id === message.chat_id
        });

        if (!message_exists && contract) {
            contract.chat.unread_messages_count++
        }

    },
    addMessages(state, messages) {
        state.chat.messages.push(...messages);
    },
    readMessage(state, message) {
        state.chat.messages.forEach(item => {
            if (item.id === message.id) {
                item.receiver_seen = 1
            }
        });
    },

    joiningChat(state){
        state.chat.target_user.chat_status = 'online';
    },
    leavingChat(state){
        state.chat.target_user.chat_status = 'offline';
    }

}
