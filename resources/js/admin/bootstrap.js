
// window._ = require('lodash');
// window.Popper = require('popper.js').default;

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}


window.api = require('axios');

window.api.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.api.defaults.withCredentials = true;
window.api.defaults.baseURL = '/admin';
window.api.defaults.headers.common['admin-api-token'] = localStorage.getItem('token') || '';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.api.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

import Echo from 'laravel-echo'

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true
});
