import {getSummary} from "@/api/summary";

export default {
  namespaced: true,
  state: () => ({
    contract: null,
    price_modification: null,
    summary_table: null,
    history: [],
    issues: [],
    loaded: false
  }),
  getters: {
    contract: state => state.contract,
    price_modification: state => state.price_modification,
    summary_table: state => state.summary_table,
    history: state => state.history,
    issues: state => state.issues,
    loaded: state => state.loaded
  },
  actions: {
    byProject({commit}, guarantee_project_id) {
      return new Promise((resolve) => {
        getSummary(guarantee_project_id).then(response => {
          let response_data = response.data.response;
          
          commit('INIT_SUMMARY', response_data.summary);
          
          commit('set', {type: 'timeline', data: response_data.timeline}, {root: true});
          commit('set', {type: 'guarantee_project', data: response_data.guarantee_project}, {root: true});
          
          resolve()
        });
      })
    },
  },
  mutations: {
    INIT_SUMMARY(state, summary) {
      state.contract = summary.contract;
      state.price_modification = summary.price_modification;
      state.summary_table = summary.summary_table;
      state.history = summary.history;
      state.issues = summary.issues;
      state.loaded = true;
    },
    CLEAR_SUMMARY(state) {
      state.contract = null;
      state.price_modification = null;
      state.summary_table = null;
      state.history = [];
      state.issues = [];
      state.loaded = false;
    },
  },
}
