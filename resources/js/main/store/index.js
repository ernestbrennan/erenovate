import Vue from 'vue'
import Vuex from 'vuex'

import getters from './getters/index'
import actions from './actions/index'
import mutations from './mutations/index'
import state from './state/index'

Vue.use(Vuex);

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations
})