require('./bootstrap');

import Vue from 'vue'
import App from './App.vue'
import VueTour from 'vue-tour'
import VueTheMask from 'vue-the-mask'
import VueCookies from 'vue-cookies'

import './plugins/vuetify'
import './plugins/validator'
import store from './store/index'
import router from './router/index'
import money from 'v-money'

Vue.use(VueCookies);
Vue.use(VueTheMask);
Vue.use(money);
Vue.use(VueTour);
Vue.use(require('vue-moment'));

Vue.filter('capitalize', function (value) {
    value = value.toString();
    return value.charAt(0).toUpperCase() + value.slice(1)
});

Vue.filter('substring_35', function (value) {
    if (value.length >= 35) {
        return value.substring(0, 35) + '...'
    }
    return value;
});

Vue.filter('substring_20', function (value) {
    if (value.length >= 20) {
        return value.substring(0, 17) + '...'
    }
    return value;
});

let main = new Vue({
    el: '#app',
    components: {App},
    router,
    store,
});
