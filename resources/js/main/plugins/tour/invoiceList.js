export default {
    data() {
        return {
            currentStep: 0,
            optionsVueTour: {
                startTimeout: 500,
            },
            steps: [
                {
                    target: '[data-v-step="ilho-1"]',
                    content: 'You can view the Project Milestone Payment Request history.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ilho-2"]',
                    content: 'You can easily navigate to this page via the Timeline.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ilho-3"]',
                    content: 'This screen show each Status, Date Stamp and Price. Click the View button to review details.',
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
            this.$store.commit('cookieTourPush', 'invoices_list')
        },
        myCustomPreviousStepCallback(currentStep) {
            this.currentStep = currentStep - 1;
        },
        myCustomNextStepCallback(currentStep) {
            this.currentStep = currentStep + 1;
        },

    },
    mounted(){
        if(window.innerWidth >= 1200 && this.$store.state.first_enter_tour['invoices_list']){
            this.$tours['tourInvoiceList'].start();
        }

    },
    beforeDestroy() {
    }
}
