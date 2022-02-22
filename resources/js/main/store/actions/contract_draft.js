import contract_draft_model from '../../models/contract_draft';
import contract_model from '../../models/contract';

export default {
    getCurrentDraft(context, guarantee_project_id) {
        return new Promise((resolve) => {
            api
                .post('contract-draft.getCurrentDraft', {guarantee_project_id: guarantee_project_id})
                .then(response => {

                    var guarantee_project = response.data.guarantee_project;
                    var contract = response.data.contract;
                    var draft = response.data.contract_draft;

                    context.commit('set', {type: 'guarantee_project', data: guarantee_project});
                    context.commit('set', {type: 'contract', data: contract});
                    context.commit('set', {type: 'contract_draft', data: draft});

                    resolve(contract.status)
                })
            ;
        })
    },
    getHistoryDraft(context, params) {
        return new Promise((resolve) => {
            api
                .post('contract-draft.getHistoryDraft', params)
                .then(response => {

                    var guarantee_project = response.data.guarantee_project;
                    var draft = response.data.contract_draft;

                    context.commit('set', {type: 'guarantee_project', data: guarantee_project});
                    context.commit('set', {type: 'contract_draft', data: draft});

                    resolve()
                })
            ;
        })
    },
    getDraftHistory(context, guarantee_project_id) {
        return new Promise((resolve) => {

            api
                .get('contract.getDraftHistory', {
                    params: {guarantee_project_id: guarantee_project_id}
                })
                .then(response => {

                    context.commit('set', {type: 'draft_history', data: response.data.drafts_history});
                    context.commit('set', {type: 'guarantee_project', data: response.data.guarantee_project});

                    resolve(response);
                });

        })
    },
    clearContractDraft(context) {
        context.commit('set', {type: 'contract_draft', data: JSON.parse(JSON.stringify(contract_draft_model)) });
    },
    clearContract(context) {
        context.commit('set', {type: 'contract', data: JSON.parse(JSON.stringify(contract_model)) });
    },
    updateUserInfo(context, current_user_info) {
        return new Promise((resolve) => {

            api
                .post('user.updateUserInfo', {
                    user_info: current_user_info
                })
                .then(response => {

                    context.commit('updateContractProp', {prop: 'current_user_info', value: response.data});
                    context.commit('updateContractDraftProp', {prop: 'interlocutor_accepted', value: 0});

                    if (context.getters.contract_draft.status !== 'new') {

                        context.commit('updateContractDraftProp', {prop: 'status', value: 'pending'});
                    }

                    resolve(response);
                });

            resolve(true);
        })
    },
    calculateSummary(context) {
        context.commit('updateDraftSummary', 'start_date');
        context.commit('updateDraftSummary', 'end_date');
        context.commit('updateDraftSummary', 'material_cost');
        context.commit('updateDraftSummary', 'service_cost');
        //context.commit('updateDraftSummary', 'total_project_price');

    },
}
