export default {

    getIssue(context, issue_id) {
        return new Promise((resolve) => {
            api
                .get('issue.getById', {
                    params: {id: issue_id}
                })
                .then(response => {

                    context.commit('set', {type: 'chat', data: response.data.chat});
                    context.commit('set', {type: 'issue', data: response.data.issue});
                    context.commit('set', {type: 'guarantee_project', data: response.data.guarantee_project});

                    context.state.chat.target_user.chat_status = 'offline';
                    context.state.user.chat_status = 'online';

                    resolve()
                })
            ;
        })
    },
    sendIssueMessage({commit}, params) {
        return new Promise((resolve) => {
            api
                .post('issue.sendMessage', params)
                .then(response => {

                    commit('addMessage', response.data.message);

                    resolve()
                })
            ;
        })
    },
}