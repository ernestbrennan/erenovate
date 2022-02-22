export default {
    data(){
        return {
            currentStep: 0,
            optionsVueTour: {
                startTimeout: 500,
            },
            steps:[
                {
                    target: '[data-v-step="fh-1"]',
                    content: 'File History is the built-in <b>Project File Manager.</b>',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="fh-2"]',
                    content: 'Automatically remembers all project files, so you don’t have to!',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="fh-3"]',
                    content: 'File History also tracks who uploaded what, and when!',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="fh-4"]',
                    content: 'Easily search your files over the time of the project. Yes!',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="fh-5"]',
                    content: 'Click Messages to go back to the Main Contract Navigation.',
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
    methods:{
        onStartTour(){
            this.currentStep = 0;
        },
        onStopTour() {
            this.$store.commit('cookieTourPush', 'file_history')
        },
        myCustomPreviousStepCallback (currentStep) {
            this.currentStep = currentStep - 1;
        },
        myCustomNextStepCallback (currentStep) {
            this.currentStep = currentStep + 1;
        },

    },
    mounted(){
        if(window.innerWidth >= 1200 && this.$store.state.first_enter_tour['file_history'] === true){
            this.$tours['tourFiles'].start();
        }
    },
    beforeDestroy() {
    }
}
