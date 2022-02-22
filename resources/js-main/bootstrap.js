window.$ = window.jQuery = require('jquery');

require('bootstrap');

window.api = require('axios');

window.api.defaults.headers.common['X-CSRF-TOKEN'] = document.head.querySelector('meta[name="csrf-token"]').content;
window.api.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.api.defaults.withCredentials = true;
window.api.defaults.baseURL = '/api';

api.interceptors.response.use(
  response => {
    return response
  },
  error => {
    
    if (error.response.status === 401) {
      return window.location = error.response.data.redirect_url;
    }
    
    return Promise.reject(Object.assign({}, error));
  }
);

import Echo from 'laravel-echo'

window.Pusher = require('pusher-js');

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: process.env.MIX_PUSHER_APP_KEY,
  cluster: process.env.MIX_PUSHER_APP_CLUSTER,
  encrypted: true
});

require('./functions');
