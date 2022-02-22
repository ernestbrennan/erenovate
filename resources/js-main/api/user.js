import request from '@/utils/request';

export function getInfoUser(guarantee_project_id) {
  return request({
    url: 'user.get-info',
    method: 'get',
    params: {
      guarantee_project_id: guarantee_project_id,
    },
  });
}

export function getDraftInfoUser(id) {
  return request({
    url: 'user.get-draft-info',
    method: 'get',
    params: {
      id: id,
    },
  });
}

export function updateDraftInfoUser(user_info) {
  return request({
    url: 'user.update-draft-info',
    method: 'post',
    data: {
      user_info: user_info
    },
  });
}

export function confirmDraftInfoUser(user_info_id) {
  return request({
    url: 'user.confirm-draft-info',
    method: 'post',
    data: {
      user_info_id: user_info_id
    },
  });
}

export function unConfirmDraftInfoUser(user_info_id) {
  return request({
    url: 'user.un-confirm-draft-info',
    method: 'post',
    data: {
      user_info_id: user_info_id
    },
  });
}

export function saveFcmToken(fcm_token) {
  return request({
    url: 'user.save-fcm-token',
    method: 'post',
    data: {
      fcm_token: fcm_token
    },
  });
}
