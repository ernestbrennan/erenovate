export default {
    getSummary(context, guarantee_project_id) {
        return new Promise((resolve) => {
            api
                .get('/summary.getByProject', {
                    params: {guarantee_project_id: guarantee_project_id}
                })
                .then(response => {
                    context.commit('set', {type: 'summary', data: response.data.summary});
                    resolve();
                })
            ;
        })
    },

    getContractSigned(context, guarantee_project_id) {
        return new Promise((resolve) => {
            api
                .get('/contract.getSigned', {params: {guarantee_project_id: guarantee_project_id}})
                .then(response => {
                    context.commit('set', {type: 'contract', data: response.data.contract});
                    context.commit('set', {type: 'contract_draft', data: response.data.contract_draft});
                    context.commit('set', {type: 'guarantee_project', data: response.data.guarantee_project});
                    resolve();
                })
            ;
        })
    },

    getProjectList(context, search_params) {
        return new Promise((resolve) => {
            api
                .get('/project.getList', {params: {query_search: search_params.query_search, state_search: search_params.state_search}})
                .then(response => {

                    context.commit('set', {type: 'projects', data: response.data.response.projects});

                    resolve()
                })
            ;
        })
    },
    getIssues(context, guarantee_project_id) {
        return new Promise((resolve) => {
            api
                .get('/issue.getByProject', {
                    params: {guarantee_project_id: guarantee_project_id}
                })
                .then(response => {

                    context.commit('set', {type: 'issues', data: response.data.issues});

                    resolve()
                })
            ;
        })
    },
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
                    resolve()
                })
            ;
        })
    },
    sendIssueMessage({commit}, params) {
        return new Promise((resolve) => {
            api
                .post('/issue.sendMessage', params)
                .then(response => {

                    commit('addMessage', response.data.message);

                    resolve()
                })
            ;
        })
    },
    receiveMessage({commit}, message) {

        commit('addMessage', message);
    },
    calculateSummary(context) {
        context.commit('updateDraftSummary', 'start_date');
        context.commit('updateDraftSummary', 'end_date');
        context.commit('updateDraftSummary', 'material_cost');
        context.commit('updateDraftSummary', 'service_cost');
    },

}
