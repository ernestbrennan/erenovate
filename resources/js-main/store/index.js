import Vue from 'vue'
import Vuex from 'vuex'

import getters from './getters/index'
import actions from './actions/index'
import mutations from './mutations/index'
import state from './state/index'

import summary_module from './modules/summary'

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    summary: summary_module
  },
  
  state,
  getters,
  actions,
  mutations,
})
