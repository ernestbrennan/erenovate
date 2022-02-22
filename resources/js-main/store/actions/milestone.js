import {getCurrentMilestone, getEditedMilestone, getByIdMilestone} from "@/api/milestone";

export default {
  getCurrentMilestone(context, guarantee_project_id) {
    return new Promise((resolve) => {
      
      getCurrentMilestone(guarantee_project_id).then(response => {
        let response_data = response.data.response;
        
        context.commit('set', {type: 'current_milestone', data: response_data.milestone});
        context.commit('set', {type: 'timeline', data: response_data.timeline});
        context.commit('set', {type: 'guarantee_project', data: response_data.guarantee_project});
        
        context.commit('set', {type: 'read_only_milestone', data: true});
        
        resolve();
      });
    })
  },
  getEditedMilestone(context, milestone_id) {
    return new Promise((resolve) => {
      getEditedMilestone(milestone_id).then(response => {
        let response_data = response.data.response;
        
        context.commit('set', {type: 'current_milestone', data: response_data.milestone});
        
        resolve();
      });
    })
  },
  getMilestoneById(context, params) {
    return new Promise((resolve) => {
  
      getByIdMilestone(params).then(response => {
          let response_data = response.data.response;
  
          context.commit('set', {type: 'current_milestone', data: response_data.milestone});
          context.commit('set', {type: 'timeline', data: response_data.timeline});
          context.commit('set', {type: 'guarantee_project', data: response_data.guarantee_project});
          
          context.commit('set', {type: 'read_only_milestone', data: true});
          
          resolve();
        });
    })
  },
}
