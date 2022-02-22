const stepsHo = [
    {
        target: '[data-v-step="nt-1"]',
        content: 'NOTES This is your Private area to easily store and access important project items.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '.step-nt-2',
        content: 'Add personal memos, upload receipts, inspirational photos, links and more. ',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="nt-3"]',
        content: 'Notes or Files uploaded to Private Area <b>are not shared</b> in the File History. ',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '.nt-4-class',
        content: 'Your Private notes and files are saved until the Project is completed.',
        params: {
            placement: 'bottom'
        }
    },
];
const stepsPro = [
    {
        target: '[data-v-step="nt-1"]',
        content: 'NOTES is your Private area to store and easily access project elements.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '.step-nt-2',
        content: 'You can add personal memos, upload receipts or store files. ',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="nt-3"]',
        content: 'Notes or Files uploaded to Private Area are not shared in the File History.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '.nt-4-class',
        content: 'Private notes and files saved until the Project is completed.',
        params: {
            placement: 'bottom'
        }
    },
];
export default {
    data() {
        return {
            currentStep: 0,
            optionsVueTour: {
                startTimeout: 500,
            },
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
        getSteps() {
            if (this.user.role === 'homeowner') {
                this.steps = stepsHo;
            } else {
                this.steps = stepsPro;
            }
        },
        onStartTour() {
            this.currentStep = 0;
        },
        onStopTour() {
            this.$store.commit('cookieTourPush', 'notes');
        },
        myCustomPreviousStepCallback(currentStep) {
            this.currentStep = currentStep - 1;
        },
        myCustomNextStepCallback(currentStep) {
            this.currentStep = currentStep + 1;
        },

    },
    created() {
        this.getSteps()
    },
    beforeDestroy() {
    },
    mounted(){
        if(window.innerWidth >= 1200 && this.$store.state.first_enter_tour['notes']){
            this.$tours['tourNotes'].start();
        }
    },
}
