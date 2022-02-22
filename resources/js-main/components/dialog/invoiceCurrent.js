const rejectText = function () {
  return window.innerWidth >= 600 ? 'DECLINE PAYMENT REQUEST' : 'DECLINE'
}
const rejextConfText = function () {
  return window.innerWidth >= 600 ? 'DECLINE CONFIRMATION' : 'DECLINE'
}
const completeInvoice = function () {
  return window.innerWidth >= 600 ? 'VERIFY PAYMENT' : 'VERIFY'
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
export const dialogReject = {
  title: 'Decline Payment Request?',
  text: `<p class="main-dialog__p">Are you sure you want to decline the payment request for this Milestone?</p>
           <p class="main-dialog__p"><b>Note: </b> this action is permanent and cannot be undone. To confirm this action, please provide a reason for declining the payment request..</p>`,
  comment: {
    placeholder: 'Provide details of declining the payment (required)',
    label: 'Reason'
  },
  activateName: 'dialogReject',
  submitBtn: rejectText(),
  cancelBtn: 'CANCEL',
}
export const dialogUnverify = {
  title: 'Decline Receipt of Payment…',
  text: `<p class="main-dialog__p">
            You are about to decline the Payment Confirmation submitted by the client for the current Milestone.
            </p>
           <p class="main-dialog__p">
            This action will cancel the Payment Confirmation made by client for this Milestone, and will keep the Payment Request open, and you can edit the Payment Request if any further work or costs are to be added.
           </p>
           <p class="main-dialog__p">
            Type CONFIRM if you have not received payment.
           </p>`,
  comment: {
    placeholder: 'Type CONFIRM (required)',
    label: 'Payment Not Recieved.',
  },
  activateName: 'dialogUnverify',
  submitBtn: rejextConfText(),
  cancelBtn: 'CANCEL',
}
export const dialogComplete = {
  title: 'Confirm Payment received…',
  text: `<p class="main-dialog__p"></p>
            <p class="main-dialog__p"></p>
            <p class="main-dialog__p"></p>`,
  comment: {
    placeholder: 'Type CONFIRM (required)',
    label: 'Received Full Milestone Payment (required)'
  },
  activateName: 'dialogComplete',
  submitBtn: completeInvoice(),
  cancelBtn: 'CANCEL',
}
