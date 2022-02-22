export default {
    data() {
        return {
            currentStep: 0,
            optionsVueTour: {
                startTimeout: 500,
            },
            stepsHo: [
                {
                    target: '[data-v-step="mls-1"]',
                    content: 'Once you agree to Project Scope Details, we notify the Pro. Project Timeline provides quick access to Project events.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="mls-2"]',
                    content: 'Click to edit Milestone details of a current or future Milestones. <b>Completed Milestones cannot be edited.</b> Your proposed Milestone edits are sent to the Pro for approval.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="mls-4"]',
                    content: 'Check Timeline to view the Milestone Status. Note: Pros can also propose a Milestone Edit.',
                    params: {
                        placement: 'bottom'
                    }
                },
            ],
            stepsPro: [
                {
                    target: '[data-v-step="mls-1"]',
                    content: 'Once client approves Contract Details, we notify you. Project Timeline provides access to Project elements.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="mls-2"]',
                    content: 'Click to update Milestone details of active or future Milestones. Completed Milestones cannot be edited. Your proposed Milestone edits are sent to client for approval.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="mls-4"]',
                    content: 'Check Timeline to view Milestone Status such as Approved or Pending. Note: Clients can also propose a Milestone Edit.',
                    params: {
                        placement: 'bottom'
                    }
                },
            ],
            steps: [],
            tourCallbacks: {
                onStop: this.onStopTour,
                onPreviousStep: this.myCustomPreviousStepCallback,
                onNextStep: this.myCustomNextStepCallback,
                onStart: this.onStartTour
            },
        }
    },
    methods: {
        insertArray(arr, index, object) {
            arr.splice(index, 0, object);
        },
        getSteps() {
            if (this.user.role === 'homeowner') {
                this.steps = this.stepsHo;
            } else {
                this.steps = this.stepsPro;
            }
        },
        onStartTour() {
            this.currentStep = 0;
        },
        onStopTour() {
            this.$store.commit('cookieTourPush', 'milestone');
        },
        myCustomPreviousStepCallback(currentStep) {
            this.currentStep = currentStep - 1;
        },
        myCustomNextStepCallback(currentStep) {
            this.currentStep = currentStep + 1;
        },
        updateStepsHo() {
            if (this.hasInvoice) {
                this.steps[1].content = 'View Payment Request:<br> For completed Milestones, you can Request a Milestone Payment via the platform. '
            } else if (false) {
            // } else if (this.has_milestone_edited) {
                this.steps[1].content = 'View Milestone Edit:<br> For active or upcoming Milestones, you or PRO can submit a Milestone edit.  The other party is notified of such edits, to then View and Accept or discuss the edits.'
            } else if (this.isMaterialEdit) {
                this.steps[1].content = 'Click to edit Milestone details of a current or future Milestones. <b>Completed Milestones cannot be edited.</b> Your proposed Milestone edits are sent to the Pro for approval.'
            } else if (this.isInProgress) {
                this.steps[1].content = 'Click to edit Milestone details of a current or future Milestones. <b>Completed Milestones cannot be edited.</b>Your proposed Milestone edits are sent to the Pro for approval.'
            }
        },
        updateStepsPro() {
            if (this.hasInvoice) {
                this.steps[1].content = 'View Payment Request:<br> For completed Milestones, you can Request a Milestone Payment via the platform. While clients pay you directly, this tracks Payment requests, and clients Accept Payment Request, and upload proof of payment. You can Confirm or Decline you received the payment.'
            } else if (false) {
            // } else if (this.has_milestone_edited) {
                this.steps[1].content = 'View Milestone Edit: <br> For active or upcoming Milestones, you or client can submit a Milestone edit.  The other party is notified of such edits, to then View and Accept or discuss the edits. Your proposed Milestone edits are sent to client for approval.'
            } else if (this.isMaterialEdit) {
                this.steps[1].content = 'Click to update Milestone details of active or future Milestones. Completed Milestones cannot be edited. Your proposed Milestone edits are sent to client for approval.'
            } else if (this.isInProgress) {
                this.steps[1].content = 'Click to update Milestone details of active or future Milestones. Completed Milestones cannot be edited. Your proposed Milestone edits are sent to client for approval.'
                const a_steps = {
                    target: '.mls-5',
                    content: 'Once a Milestone is completed, click button to generate and track a Payment Request for the Milestone.',
                    params: {
                        placement: 'bottom'
                    }
                };
                this.steps.push(a_steps);
            }
        },

    },
    created() {
        this.getSteps();
    },
    mounted() {
        if (window.innerWidth >= 1200 && this.$store.state.first_enter_tour['milestone']) {
            this.$tours['tourMilestone'].start();
        }
    },
    beforeUpdate() {
        if (this.user.role === 'homeowner') {
            this.updateStepsHo();
        } else {
            this.updateStepsPro();
        }
    },
}
