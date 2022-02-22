const proposalConfText = function () {
  return window.innerWidth >= 600 ? 'SUBMIT THE EDIT' : 'SUBMIT'
}
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
export const proposalConfDialog = {
  title: 'Submit Milestone Edit…',
  text: `<p class="main-dialog__p">
                You are about to propose milestone edits, which will be reviewed by the Pro. We will notify you once proposed edits are accepted or declined.
           </p>`,
  comment: {
    placeholder: 'Provide a brief reason for the edit (required)',
    label: 'Enter reason for the edit (required)',
  },
  activateName: 'proposalConfDialog',
  submitBtn: proposalConfText(),
  cancelBtn: 'CANCEL',
}

export const acceptEditDialog = {
  title: 'Accept the Milestone Edit…',
  text: `<p class="main-dialog__p">You are about to accept the proposed edits to this Milestone.</p>
           <p class="main-dialog__p">By accepting the milestone update, you agree to the new Milestone details.</p>`,
  comment: {
    placeholder: 'Type CONFIRM (required)',
    label: 'I accept Milestone Edit',
  },
  activateName: 'acceptEditDialog',
  submitBtn: 'ACCEPT',
  cancelBtn: 'CANCEL',
}
export const rejectEditDialog = {
  title: 'Decline the Milestone Edit…',
  text: `<p class="main-dialog__p">You are about to decline the proposed Milestone edits.</p>
            <p class="main-dialog__p"><b>Note:</b> This action is cannot be undone.</p>`,
  comment: {
    placeholder: 'Type CONFIRM (required)',
    label: 'I Decline Milestone Edit',
  },
  activateName: 'rejectEditDialog',
  submitBtn: 'DECLINE',
  cancelBtn: 'CANCEL',
}
