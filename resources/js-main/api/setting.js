import request from '@/utils/request';

export function getSettings() {
  return request({
    url: 'setting.get',
    method: 'get',
  });
}

export function changePasswordSettings(current_password, new_password) {
  return request({
    url: 'setting.change-password',
    method: 'post',
    data: {
      current_password: current_password,
      new_password: new_password,
    }
  });
}

export function saveSettings(settings) {
  return request({
    url: 'setting.save',
    method: 'post',
    data: {
      ...settings
    }
  });
}
