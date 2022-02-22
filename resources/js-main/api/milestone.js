import request from '@/utils/request';

export function getCurrentMilestone(project_id) {
  return request({
    url: 'milestone.get-current',
    method: 'get',
    params: {
      project_id: project_id,
    },
  });
}

export function getEditedMilestone(milestone_id) {
  return request({
    url: 'milestone.get-edited',
    method: 'get',
    params: {
      milestone_id: milestone_id,
    },
  });
}

export function getByIdMilestone(params) {
  return request({
    url: 'milestone.get-by-id',
    method: 'get',
    params: params,
  });
}

export function editMilestone(milestone, comment) {
  return request({
    url: 'milestone.edit',
    method: 'post',
    data: {
      milestone: milestone,
      comment: comment,
    },
  });
}

export function acceptMilestone(milestone, comment) {
  console.log('acceptMilestone')
  return request({
    url: 'milestone.accept',
    method: 'post',
    data: {
      milestone: milestone,
      comment: comment,
    },
  });
}

export function rejectMilestone(milestone, comment) {
  console.log('rejectMilestone')
  return request({
    url: 'milestone.reject',
    method: 'post',
    data: {
      milestone: milestone,
      comment: comment,
    },
  });
}

