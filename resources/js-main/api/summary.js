import request from '@/utils/request';

export function getSummary(id) {
  return request({
    url: 'summary.by-project',
    method: 'get',
    params: {
      id: id,
    },
  });
}
