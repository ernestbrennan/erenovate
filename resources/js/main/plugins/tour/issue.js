const stepsHo = [
    {
        target: '.issue-tour-1',
        content: 'Back to Summary:<br> Takes you back to the Completed Project Summary.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '.issue-tour-2',
        content: 'Issue Resolution Interface:<br> To ensure timely resolution, and to ensure eRenovate lends a hand if required, use this interface to communicate with the Pro. All the dialogue will be shown here.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '.issue-ho-3a',
        content: 'CLOSE ISSUE:<br> Once the Issue has been resolved, only you or an eRenovate Success Advisor can close the Issue.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '.issue-tour-3',
        content: 'Issue Resolution Interface:<br> You can upload supporting images by clicking the upload button.',
        params: {
            placement: 'bottom'
        }
    },
];
const stepsPro = [
    {
        target: '.issue-tour-1',
        content: 'Back to Summary:<br>Takes you back to the Completed Project Summary.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '.issue-tour-2',
        content: 'Issue Resolution Interface:<br> It is required both You and client communicate via the Issue Interface to keep both parties on the same page.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '.issue-tour-3',
        content: 'Issue Resolution Interface:<br> You can upload supporting images by clicking the upload button. Once issue is resolved, the client or eRenovate can close the issue..',
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
        insertArray(arr, index, object) {
            arr.splice(index, 0, object);
        },
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
            this.$store.commit('cookieTourPush', 'issue')
        },
        myCustomPreviousStepCallback(currentStep) {
            this.currentStep = currentStep - 1;
        },
        myCustomNextStepCallback(currentStep) {
            this.currentStep = currentStep + 1;
        },
        updateStepsHo(){

        },
        updateStepsPro(){
        },

    },
    created() {
        this.getSteps();
    },
    mounted(){
        if(window.innerWidth >= 1200 && this.$store.state.first_enter_tour['issue']){
            this.$tours['tourIssue'].start();
        }
    },
    beforeUpdate(){
    },
    beforeDestroy() {
    },
}
