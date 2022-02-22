import request from '@/utils/request';

export function signIn(email, password) {
  return request({
    url: 'user.sign-in',
    method: 'post',
    data: {
      email,
      password,
    },
  });
}

export function signUp(email, password, first_name, role) {
  return request({
    url: 'user.sign-up',
    method: 'post',
    data: {
      email: email,
      password: password,
      first_name: first_name,
      role: role
    },
  });
}


export function logout() {
  return request({
    url: 'user.logout',
    method: 'post',
  });
}
