export default {
    data() {
        return {
            currentStep: 0,
            optionsVueTour: {
                startTimeout: 500,
            },
            steps: [
                {
                    target: '[data-v-step="ci-1"]',
                    content: 'Once both parties agree a Milestone is completed, Press the Complete Milestone button',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ci-3"]',
                    content: 'Simply fill in the required fields! You can use an Invoice # from your current system)',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '.ci-4',
                    content: 'Enter the Submitted Date, and Due Date of the Invoice as agreed between you and client.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ci-5"]',
                    content: 'Listing Material items and Labour costs is an option. This key feature helps compare estimated cost vs final cost of Project Milestone.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ci-7"]',
                    content: 'Milestone Summary; Insert final Cost, plus tax to generate a Final Milestone Payment  Request',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '.ci-8-class',
                    content: 'Submit Payment Request notifies client to review details and submit payment.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ci-9"]',
                    content: 'Payment Request In Progress. Once client pays you <b>directly</b>, they click Milestone Paid; You will Verify (or deny) payment was received.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ci-10"]',
                    content: 'This changes the Milestone status, or Completed Project if it’s the last Milestone or a Single Milestone Project.',
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
            this.$store.commit('cookieTourPush', 'invoice_create')
        },
        myCustomPreviousStepCallback(currentStep) {
            this.currentStep = currentStep - 1;
        },
        myCustomNextStepCallback(currentStep) {
            this.currentStep = currentStep + 1;
        },

    },
    beforeDestroy() {
    },
    mounted(){
        if( window.innerWidth >= 1200 && this.$store.state.first_enter_tour['invoice_create']){
            this.$tours['tourInvoice'].start();
        }
    },
}
