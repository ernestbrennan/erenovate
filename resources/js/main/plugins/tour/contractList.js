const stepsHo = [
    {
        target: '[data-v-step="cl-1"]',
        content: 'Once you Hire a Verified Pro, your projects are listed in Contracts ',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="cl-2"]',
        content: 'Easily search your Projects  if you have a few on the go',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="cl-3"]',
        content: 'Click the Open Button to open the Project Details',
        params: {
            placement: 'bottom'
        }
    },
];
const stepsPro = [
    {
        target: '[data-v-step="cl-1"]',
        content: 'When a client hires you, the Project is listed in the Contracts Tab',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="cl-2"]',
        content: 'You can quickly search your Projects',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="cl-3"]',
        content: 'Click the Open Button to open the Project Details',
        params: {
            placement: 'bottom'
        }
    },
];
export default {
    data(){
        return {
            timer_couter: 0,
            currentStep: 0,
            optionsVueTour: {
                startTimeout: 500,
            },
            steps:[],
            contractsCallbacks: {
                onStop: this.onStopTour,
                onPreviousStep: this.myCustomPreviousStepCallback,
                onNextStep: this.myCustomNextStepCallback,
                onStart: this.onStartTour
            },
        }
    },
    methods:{
        getSteps(){
            if (this.user.role === 'homeowner'){
                this.steps = stepsHo;
            } else {
                this.steps = stepsPro;
            }
        },
        onStartTour(){
            return false
        },
        onStopTour() {
            this.currentStep = 0;
            this.$store.commit('cookieTourPush', 'projects_list')
        },
        myCustomPreviousStepCallback (currentStep) {
            this.currentStep = currentStep - 1;
        },
        myCustomNextStepCallback (currentStep) {
            this.currentStep = currentStep + 1;
        },
        startMountTour(){
            if(this.$route.name !== 'projects-list' ||
                this.$store.state.first_enter_tour['projects_list'] === false){
                return false }
            if(this.$store.state.global_loader === false){
                this.$tours['tourContracts'].start();
            } else {
                const timer = setTimeout(() => {
                    if(this.timer_couter < 20){
                        this.startMountTour()
                    }
                    this.timer_couter++;
                    clearTimeout(timer)
                }, 1500)
            }
        },
    },
    created(){
        this.getSteps()
    },
    mounted(){
        if( window.innerWidth >= 1200 ){
            this.startMountTour()
        }
    },
    beforeDestroy() {
    }
}
