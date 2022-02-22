import state from "./state";
export default {
    dialogMain: state => state.dialogMain,
    errorAlert: state => state.errorAlert,
    user : state => state.user,
    chat : state => state.chat,
    contract : state => state.contract,
    contracts : state => state.contracts,
    contract_draft : state => state.contract_draft,
    guarantee_project: state => state.guarantee_project,
    readOnly: state => state.readOnly,
    timeline: state => state.timeline,
    invoice_summary: state => state.invoice_summary,
    summary: state => state.summary,
    draft_summary: state => state.contract_draft.summary,
    projects: state => state.projects,
    issues: state => state.issues,
    issue: state => state.issue,


}