import request from '@/utils/request';

export function listArchivedDraft(query_search) {
  return request({
    url: 'archived-draft.list',
    method: 'get',
    params: {
      query_search: query_search,
    },
  });
}

export function getByIdArchivedDraft(id) {
  return request({
    url: 'archived-draft.get-by-id',
    method: 'get',
    params: {
      id: id,
    },
  });
}

export function saveArchivedDraft(params) {
  return request({
    url: 'archived-draft.save',
    method: 'post',
    data: params,
  });
}

export function updateArchivedDraft(params) {
  return request({
    url: 'archived-draft.update',
    method: 'post',
    data: params,
  });
}

export function deleteArchivedDraft(id) {
  return request({
    url: 'archived-draft.delete',
    method: 'post',
    data: {
      id: id,
    },
  });
}
