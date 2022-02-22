export default {
    getChat(context, guarantee_project_id) {
        return new Promise((resolve) => {
            api
                .get('chat.getByProject', {params: {guarantee_project_id: guarantee_project_id}})
                .then(response => {


                    context.commit('set', {type: 'chat', data: response.data.chat});
                    context.commit('set', {type: 'guarantee_project', data: response.data.guarantee_project});

                    context.state.chat.target_user.chat_status = 'offline';
                    context.state.user.chat_status = 'online';

                    resolve()
                })
            ;
        })
    },
    getList(context, guarantee_project_id) {

        return new Promise((resolve) => {
            api
                .post('note.getList', {guarantee_project_id: guarantee_project_id})
                .then(response => {
                    if (response.status === 200) {

                        context.commit('set', {type: 'notes', data: response.data.notes});
                        context.commit('set', {type: 'guarantee_project', data: response.data.guarantee_project});

                        resolve()
                    }
                })
            ;
        })
    },
    sendNote({commit}, params) {
        return new Promise((resolve) => {
            api
                .post('note.sendNote', params)
                .then(response => {

                    commit('addNote', response.data.note);

                    resolve()
                })
            ;
        })
    },
    sendMessage({commit}, params) {
        return new Promise((resolve, reject) => {
            api
                .post('message.sendMessage', params)
                .then(response => {

                    commit('addMessage', response.data.message);

                    resolve()
                })
                .catch(e => {
                    reject(e)
                })
            ;
        })
    },
    getMessages({commit}, params) {
        return new Promise((resolve) => {
            api
                .get('message.getList', {params: params})
                .then(response => {

                    commit('addMessages', response.data);

                    resolve()
                })
            ;
        })
    },
    receiveMessage({commit}, message) {

        commit('addMessage', message);
    },
    readMessage({commit}, message) {

        commit('readMessage', message);
    },
    getFileHistory(context, guarantee_project_id) {
        return new Promise((resolve) => {

            api
                .get('file.getFileHistory', {
                    params: {guarantee_project_id: guarantee_project_id}
                })
                .then(response => {

                    context.commit('set', {type: 'fileHistory', data: response.data.file_history});

                    resolve(response);
                });

        })
    },
    joiningChat({commit}) {
        commit('joiningChat');
    },
    leavingChat({commit}) {
        commit('leavingChat');
    },
}