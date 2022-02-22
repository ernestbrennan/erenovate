import request from '@/utils/request';

export function byProjectChat(guarantee_project_id) {
  return request({
    url: 'chat.by-project',
    method: 'get',
    params: {
      guarantee_project_id: guarantee_project_id,
    },
  });
}
export function deleteArchivedDraft(id) {
  return request({
    url: 'chat.get-notes',
    method: 'post',
    data: {
      id: id,
    },
  });
}
