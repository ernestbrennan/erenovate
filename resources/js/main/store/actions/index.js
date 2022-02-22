import messenger from './messenger';
import contract from './contract';
import contract_draft from './contract_draft';
import invoice from './invoice';
import milestone from './milestone';
import issue from './issue';
import settings from './settings';

export default {
    set({commit}, {type, data}) {
        commit('set', {type: type, data: data})
    },
    openDialog({commit}, {dialog_state, dialog_model, dialog_type, dialog_fun}) {
        commit('renderDialog', true);
        const timer = setTimeout(() => {
            commit('setDialog', {dialog_state, dialog_model, dialog_type, dialog_fun});
            clearTimeout(timer)
        }, 100)

    },
    getSummary(context, guarantee_project_id) {
        return new Promise((resolve) => {
            api
                .get('summary.getByProject', {
                    params: {guarantee_project_id: guarantee_project_id}
                })
                .then(response => {

                    context.commit('set', {type: 'timeline', data: response.data.timeline});
                    context.commit('set', {type: 'summary', data: response.data.summary});
                    context.commit('set', {type: 'guarantee_project', data: response.data.guarantee_project});

                    resolve()
                })
            ;
        })
    },
    getArchivedDraftsList(context, query_search) {
        return new Promise((resolve) => {

            api
                .get('archived-draft.getList', {
                    params: {query_search: query_search}
                })
                .then(response => {

                    context.commit('set', {type: 'archived_drafts', data: response.data.archived_drafts});

                    resolve()
                })
            ;
        })
    },


    ...messenger,
    ...contract,
    ...contract_draft,
    ...invoice,
    ...milestone,
    ...issue,
    ...settings
}
