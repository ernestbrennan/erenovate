const proposalText = function () {
  return window.innerWidth >= 600 ? 'SUBMIT THE EDIT' : 'SUBMIT'
};
export const proposeDialog = {
  title: 'Submit Materials Update…',
  text:
    `<p class="main-dialog__p">
                You are about to propose an update to the List of Materials for this milestone. This update must be accepted by the client.
            </p>
            <p class="main-dialog__p">
                You can still add/propose future material edits if the client accepts or declines this current update.
            </p>
            <p class="main-dialog__p">
            <b>Note:</b> Completing this Milestone and requesting Milestone Payment is not available until the other party approves the proposed Materials update.
            </p>`,
  comment: {
    placeholder: 'Write a brief description of materials edit',
    label: 'Provide reason for Materials update (required)'
  },
  activateName: 'proposeDialog',
  submitBtn: proposalText(),
  cancelBtn: 'CANCEL',
};
export const cancelProposeDialog = {
  title: 'Cancel Materials Edit',
  text: `<p class="main-dialog__p">Do you want to cancel the change in Materials?</p>
            <p class="main-dialog__p"><b>Note:</b> this action cannot be undone, and any Material edits will not be saved.</p>`,
  activateName: 'cancelProposeDialog',
  comment: false,
  submitBtn: 'CANCEL',
  cancelBtn: 'CLOSE',
};
export const completeMilestoneDialog = {
  title: 'Milestone Completed…',
  text: `<p class="main-dialog__p">This Milestone is now completed and ‘closed’, you will now be directed to create a Milestone Payment Request.
</p>
<p class="main-dialog__p">Once your Payment Request is submitted, client is notified and required to upload proof of payment to eRenovate. Once proof of payment is uploaded, client will ask you to confirm receiving payment.</p>
<p class="main-dialog__p">Once you confirm the payment, the Milestone status will automatically change to Completed.</p>`,
  comment: false,
  submitBtn: 'CREATE PAYMENT REQUEST',
  activateName: 'completeMilestoneDialog',
  cancelBtn: false,
};
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
};
