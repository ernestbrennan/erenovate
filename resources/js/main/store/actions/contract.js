export default {
    getContractsList(context, query_search) {
        return new Promise((resolve) => {
            api
                .get('contract.getList', {
                    params: {query_search: query_search}
                })
                .then(response => {

                    context.commit('set', {type: 'contracts', data: response.data.projects});

                    resolve()
                })
            ;
        })
    },
    getContract(context, guarantee_project_id) {
        return new Promise((resolve) => {
            api
                .post('contract.getByProject', {guarantee_project_id: guarantee_project_id})
                .then(response => {

                    context.commit('set', {type: 'contract', data: response.data.contract});
                    context.commit('set', {type: 'guarantee_project', data: response.data.guarantee_project});

                    resolve();
                })
            ;
        })
    },
    getContractSigned(context, guarantee_project_id) {
        return new Promise((resolve) => {
            api
                .post('contract.getContractSigned', {guarantee_project_id: guarantee_project_id})
                .then(response => {

                    context.commit('set', {type: 'contract', data: response.data.contract});
                    context.commit('set', {type: 'contract_draft', data: response.data.contract_draft});
                    context.commit('set', {type: 'guarantee_project', data: response.data.guarantee_project});
                    context.commit('set', {type: 'timeline', data: response.data.timeline});

                    resolve();
                })
            ;
        })
    },
    withdrawContract(context, params) {
        return new Promise((resolve) => {
            api
                .post('project.withdraw', params)
                .then(response => {

                    resolve(response)
                })
            ;
        })
    },
}