import {updateDraftSummary} from './calculateDraftSummary'
import {updateInvoiceSummary} from './calculateInvoiceSummary'
import {validateDropZone, setValidDropZone, mountedValidDropZone, removeValidDropZone} from "./dropValidate";
import {cookieTourPush} from "./toursMutations"
import messenger from "./messenger"

export default {
    updateDraftSummary,
    updateInvoiceSummary,
    validateDropZone,
    setValidDropZone,
    mountedValidDropZone,
    removeValidDropZone,
    cookieTourPush,

    ...messenger,

    drawer(state, val) {
        state.drawer = val
    },
    setContHeight(state, val) {
        state.containerHeight = val
    },

    setContractDropzone(state, {type, data}) {
        state.dropZoneContract[type] = data
    },

    setDialog(state, {dialog_state,dialog_model, dialog_type, dialog_fun}) {
        state.dialogMain.contentObj = dialog_model;
        state.dialogMain.openDialog = dialog_state;
        state.dialogMain.type = dialog_type;
        state.dialogMain.dialogFun = dialog_fun;
    },
    renderDialog(state, val) {
        state.renderDialog = val
    },

    newDraftDialog(state, {type, data}) {
        state.contractNewDraftDialog[type] = data
    },
    set(state, {type, data}) {
        state[type] = data
    },

    addNote(state, note) {
        state.notes.unshift(note)
    },

    updateContractDraftProp(state, {prop, value}) {
        state.contract_draft[prop] = value;
    },

    updateContractProp(state, {prop, value}) {
        state.contract[prop] = value;
    },

    setMilestoneToInvoice(state, milestone) {
        state.invoice.milestone = milestone;
    }
};
