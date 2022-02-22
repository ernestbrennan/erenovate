import messenger from './messenger';
import contract from './contract';
import contract_draft from './contract_draft';
import invoice from './invoice';
import milestone from './milestone';
import issue from './issue';
import settings from './settings';

export default {
  set({commit}, {type, data}) {
    commit('set', {type: type, data: data})
  },
  openDialog({commit}, {dialog_state, dialog_model, dialog_type, dialog_fun}) {
    commit('renderDialog', true);
    const timer = setTimeout(() => {
      commit('setDialog', {dialog_state, dialog_model, dialog_type, dialog_fun});
      clearTimeout(timer)
    }, 100)
  },
  
  ...messenger,
  ...contract,
  ...contract_draft,
  ...invoice,
  ...milestone,
  ...issue,
  ...settings
}
