export const currentDialog = {
  title: '',
  text: '',
  comment: {
    placeholder: '',
    label: '',
  },
  activateName: 'currentDialog',
  submitBtn: 'CREATE',
  cancelBtn: 'CANCEL',
}
const proposeBtnText = function () {
  return window.innerWidth >= 768 ? 'PROPOSE DRAFT' : 'OK'
}
const proposeMainBtnText = function () {
  return window.innerWidth >= 768 ? 'ACCEPT DRAFT' : 'ACCEPT'
}
export const dialogContractPropose = {
  title: 'Confirm Project Scope',
  text:
    `<p class="main-dialog__p">
            You are about to propose a Project Scope Draft to the second party. This action create the first Draft for this project.
        </p>
        <p class="main-dialog__p">
            By proposing the draft, you agree to the details.
        </p>`,
  comment: {
    placeholder: 'Provide brief comment to support the draft (required)',
    label: 'Comment or Description'
  },
  activateName: 'dialogContractPropose',
  submitBtn: proposeBtnText(),
  cancelBtn: 'CANCEL',
}
const rejectBtnText = function () {
  return window.innerWidth >= 768 ? 'DECLINE DRAFT' : 'DECLINE'
}
export const dialogRejectDraft = {
  title: 'Decline the Draft?',
  text:
    `<p class="main-dialog__p">
           Do you wish to decline the Project Scope Draft?
        </p>
        <p class="main-dialog__p">
            <b>Warning</b>: This action cannot be undone.
        </p>
        <p class="main-dialog__p">
            To confirm the action, please provide your reason for declining the Project Scope Draft below…
        </p>`,
  comment: {
    label: 'Reason for Declining Draft',
    placeholder: 'Provide your reason to inform the client (required)',
  },
  activateName: 'dialogRejectDraft',
  submitBtn: rejectBtnText(),
  cancelBtn: 'CANCEL',
}
export const dialogAcceptDraft = {
  title: 'Accept Project Scope',
  text:
    `<p class="main-dialog__p">
           Great. Accepting the Project Scope Draft officially starts the project, to be covered by the eRenovate Guarantee.
        </p>
        <p class="main-dialog__p">
            By accepting the Project Scope, you agree to the details.
        </p>
        <p class="main-dialog__p">
            To confirm, please type CONFIRM in the field below.
        </p>`,
  comment: {
    placeholder: 'Type CONFIRM to accept the Project Scope',
    label: 'Confirm (required)'
  },
  activateName: 'dialogAcceptDraft',
  submitBtn: proposeMainBtnText(),
  cancelBtn: 'CANCEL',
}
export const dialogPayFee = {
  title: 'Accept Project Draft',
  text:
    `<p class="main-dialog__p">
            You are about to accept Project Draft and start eRenovate Guarantee Contract.
        </p>
        <p class="main-dialog__p">
            <b>Warning</b>: this action is permanent. By accepting this draft you agree with it's terms. To prevent
            accidental action please write a reason of your confirmation bellow.
        </p>
`,
  comment: {
    placeholder: 'Summary statement of draft (required)',
    label: 'Comment'
  },
  activateName: 'dialogPayFee',
  submitBtn: 'PROPOSE PROJECT',
  cancelBtn: 'CANCEL',
}
const submitPropBtnText = function () {
  return window.innerWidth >= 768 ? 'SUBMIT PROPOSAL' : 'SUBMIT'
}
export const dialogProposalConfirm = {
  title: 'Confirm Draft Edit…',
  text:
    `<p class="main-dialog__p">
            You are about to submit different terms to the project draft.
        </p>
        <p class="main-dialog__p">
           To confirm this action, please provide reason for your proposed edits.
        </p>`,
  comment: {
    placeholder: 'Provide a reason for your edit suggestions. (required)',
    label: 'Reason for Edit'
  },
  activateName: 'dialogProposalConfirm',
  submitBtn: submitPropBtnText(),
  cancelBtn: 'CANCEL',
}
