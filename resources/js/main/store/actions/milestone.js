export default {
    getCurrentMilestone(context, guarantee_project_id) {
        return new Promise((resolve) => {

            api
                .get('milestone.getCurrent', {
                    params: {guarantee_project_id: guarantee_project_id}
                })
                .then(response => {

                    context.commit('set', {type: 'current_milestone', data: response.data.milestone});
                    context.commit('set', {type: 'timeline', data: response.data.timeline});
                    context.commit('set', {type: 'guarantee_project', data: response.data.guarantee_project});

                    context.commit('set', {type: 'read_only_milestone', data: true});

                    resolve();
                });
        })
    },
    getEditedMilestone(context, milestone_id) {
        return new Promise((resolve) => {

            api
                .get('milestone.getEdited', {
                    params: {milestone_id: milestone_id}
                })
                .then(response => {

                    context.commit('set', {type: 'current_milestone', data: response.data.milestone});

                    resolve();
                });
        })
    },
    getMilestoneById(context, params) {
        return new Promise((resolve) => {

            api
                .get('milestone.getById', {
                    params: params
                })
                .then(response => {

                    context.commit('set', {type: 'current_milestone', data: response.data.milestone});
                    context.commit('set', {type: 'timeline', data: response.data.timeline});
                    context.commit('set', {type: 'guarantee_project', data: response.data.guarantee_project});

                    context.commit('set', {type: 'read_only_milestone', data: true});

                    resolve();
                });
        })
    },
}