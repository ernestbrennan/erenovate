import dialog from './dialog';
import contract_draft from './contract_draft';
import contract from './contract';
import invoice from './invoice';
import messenger from './messenger';
import project from './project';
import milestone from './milestone';
import tours from './tours'

export default {
  
  ...dialog,
  ...contract_draft,
  ...invoice,
  ...messenger,
  ...contract,
  ...project,
  ...milestone,
  ...tours,
  
  //Boolean
  chat_audio: document.getElementById('ChatAudio') || null,
  routerRedirect: '',
  drawer: true,
  global_loader: false,
  readOnly: false,
  containerHeight: false,
  contractHeightHeader: false,
  chatBodyHeight: false,
  dialogChatWithdraw: false,
  proposeContract: false,
  hasNewBatch: false,
  dropZoneValidMap: {
    valid: true,
    drops: [],
  },
  dropZoneContract: {
    valid: false,
    hasFiles: false,
  },
  slideSearchContracts: false,
  globLoading: Boolean(false),
  errorAlert: null,
  user: window.USER,
  totalMaterials: 0,
  
  timeline: null,
  issue: {
    status: 'closed'
  },
  
  setting: null,
  archived_drafts: []
};
