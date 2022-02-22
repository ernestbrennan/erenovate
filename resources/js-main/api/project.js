import request from '@/utils/request';

export function getOrCreateProject(project_id, contractor_id) {
  return request({
    url: 'project.get-or-create',
    method: 'post',
    data: {
      project_id: project_id,
      contractor_id: contractor_id
    }
  });
}

export function visitProject(id) {
  return request({
    url: 'project.visit',
    method: 'post',
    data: {
      id: id,
    }
  });
}

export function checkVisitedProject(id) {
  return request({
    url: 'project.check-visited',
    method: 'get',
    params: {
      id: id,
    }
  });
}

export function withdrawProject(params) {
  return request({
    url: 'project.withdraw',
    method: 'post',
    data: params
  });
}

export function inviteHOProject(params) {
  return request({
    url: 'project.invite-ho',
    method: 'post',
    data: params
  });
}

