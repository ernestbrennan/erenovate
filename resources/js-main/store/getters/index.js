
export default {
    dialogMain: state => state.dialogMain,
    errorAlert: state => state.errorAlert,
    user : state => state.user,
    target_user: state => state.chat.target_user,
    chat : state => state.chat,
    contract : state => state.contract,
    contracts : state => state.contracts,
    contract_draft : state => state.contract_draft,
    guarantee_project: state => state.guarantee_project,
    notes: state => state.notes,
    fileHistory: state => state.fileHistory,
    readOnly: state => state.readOnly,

    draft_summary: state => state.contract_draft.summary,
    draft_history: state => state.draft_history,
    timeline: state => state.timeline,

    invoice_read_only: state => state.invoice_read_only,
    has_current_invoice: state => state.has_current_invoice,
    invoices: state => state.invoices,
    invoice: state => state.invoice,
    invoice_summary: state => state.invoice_summary,

    read_only_milestone: state => state.read_only_milestone,
    current_milestone: state => state.current_milestone,

    summary: state => state.summary,
    issue: state => state.issue,

    setting: state => state.setting,

    show_messenger_scroll_to_bottom: state => state.show_messenger_scroll_to_bottom,
    scroll_message_count: state => state.scroll_message_count,

    invite_info: state => state.contract.invite_info,
    archived_drafts: state => state.archived_drafts,




};