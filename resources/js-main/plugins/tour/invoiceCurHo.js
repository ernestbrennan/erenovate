export default {
    data() {
        return {
            currentStep: 0,
            optionsVueTour: {
                startTimeout: 500,
            },
            steps: [
                {
                    target: '[data-v-step="ciho-1"]',
                    content: 'Once both parties have agreed a Milestone is completed, the Pro sends you a Milestone Payment Request.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '.ciho-2',
                    content: 'You can view all the details of the Payment Request once you are notified.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ciho-3"]',
                    content: 'The Pro can list Materials cost and Labour costs for each Milestone– it’s an option.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ciho-4"]',
                    content: 'Milestone Summary; the platform generates a final Milestone Payment Request.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '.ciho-5-class',
                    content: 'You receive a Payment Request alert to review details. You have the ability to accept or request an edits. You send payment to the Pro directly.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '.ciho-6-class',
                    content: 'You pay the Pro directly and click Milestone Paid. We recommend uploading proof of payment onto the Guarantee Platform. The Pro will then Confirm (or deny) payment received.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '.ciho-7-class',
                    content: 'This updates the Milestone status, or Completes the Project if it was the last Milestone or a Single Milestone Project.',
                    params: {
                        placement: 'bottom'
                    }
                },

            ],
            tourCallbacks: {
                onStop: this.onStopTour,
                onPreviousStep: this.myCustomPreviousStepCallback,
                onNextStep: this.myCustomNextStepCallback,
                onStart: this.onStartTour
            },
        }
    },
    methods: {
        onStartTour() {
            this.currentStep = 0;
        },
        onStopTour() {
            this.$store.commit('cookieTourPush', 'invoice_current');
        },
        myCustomPreviousStepCallback(currentStep) {
            this.currentStep = currentStep - 1;
        },
        myCustomNextStepCallback(currentStep) {
            this.currentStep = currentStep + 1;
        },

    },
    mounted(){
        if(window.innerWidth >= 1200 &&
            this.$store.state.first_enter_tour['invoice_current'] &&
            this.user.role === 'homeowner'){
            this.$tours['tourCurrInvoice'].start();
        }
    },
    beforeDestroy() {
    },
}
