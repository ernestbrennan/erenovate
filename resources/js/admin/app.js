require('./bootstrap');
import Vue from 'vue'
import './plugins/vuetify'
import Vuelidate from 'vuelidate'
import App from './components/App'
import store from './store/index'
import router from './router/index'

import VeeValidate from 'vee-validate'
import {Validator} from 'vee-validate'
import VueTour from 'vue-tour'
import money from 'v-money'

Vue.use(money);

Vue.use(Vuelidate);
Vue.use(VueTour);
Vue.use(require('vue-moment'));

Vue.use(VeeValidate, {
    events: 'blur|change',
});


Validator.extend('formatted_price', {
    getMessage(field, params, data) {
        return (data && data.message) || 'Price invalid'
    },
    validate(value) {
        return formatPrice(value) > 0;
    }
});

api.interceptors.response.use(
    response => {

        return response
    },

    error => {

        if (error.response.status === 401) {
            store.state.global_loader = false
            router.push({name: 'login'})

        }
    }
);

let app = new Vue({
    el: '#app',
    components: {App},
    router,
    store,
});