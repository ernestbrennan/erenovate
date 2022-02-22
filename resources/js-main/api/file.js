import request from '@/utils/request';

export function getHistoryFile(guarantee_project_id) {
  return request({
    url: 'file.get-history',
    method: 'get',
    params: {
      guarantee_project_id: guarantee_project_id,
    },
  });
}
