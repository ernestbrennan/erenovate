const stepsHo = [
    {
        target: '[data-v-step="ccd-1"]',
        content: 'Let’s get familiar with your Project Scope Details…',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-2"]',
        content: 'Project Scope includes project details and expectations.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-3"]',
        content: 'The Project Scope of Work has 4 sections; Parties, Description, Milestones and Summary.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-4"]',
        content: 'Parties to the Agreement details will be approved by both you and the Pro.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-7"]',
        content: 'Project Scope details includes Title, Description, and Attachments.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-8"]',
        content: 'File Attachments must include the final written Quote or Contract signed by you and your client.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-9"]',
        content: 'It is mandatory to attach your final, signed Project, with details of project and other supporting documents that are part of your Project.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-10"]',
        content: 'Good news, you can download documents anytime, from anywhere!',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-11"]',
        content: 'Project Scopes may have a Single or Several Milestones, based on what you and the Pro agree to.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-13"]',
        content: 'Materials section may be used to identify materials that changed from the initial scope.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-14"]',
        content: 'The Summary section of the Project Scope is auto-generated for Projects with multiple Milestones.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-15"]',
        content: 'Project Summary calculates approx. start and end dates and total cost of each and all milestones, automatically! Nice!',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-17"]',
        content: 'You can send Project Scope Draft to the Pro if you complete all the details. We say, leave this up to the Pro.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-17"]',
        content: '– The Guarantee platform notifies you when the Project Scope Draft is ready for your approval. Once you approve it, we notify the Pro and the Project officially is started',
        params: {
            placement: 'bottom'
        }
    },

];

const stepsPro = [
    {
        target: '[data-v-step="ccd-1"]',
        content: 'Let’s create a Contract Draft for this project! ',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-2"]',
        content: 'Project Scope includes project details and expectations.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-3"]',
        content: 'The Project Scope of Work has 4 sections; Parties, Description, Milestones and Summary.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-4"]',
        content: 'Parties to the Agreement confirm your legal business entity, and client’s details.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-5"]',
        content: 'Fill in the details, you and client approve each others details.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-6"]',
        content: 'At any point, Cancel Creation to stop creating the Project Scope!',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-7"]',
        content: 'Project Scope details includes Title, Description, and Attachments.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-8"]',
        content: 'File Attachments must include the final written Quote or Contract signed by you and your client.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-9"]',
        content: 'It is mandatory to attach your final, signed Contract, with details of project and other supporting documents that are part of your Contract.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-10"]',
        content: 'Good news, you can download documents anytime, from anywhere!',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-11"]',
        content: 'Projects can have a Single or Several Milestones, based on what you and the client agree to.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-12"]',
        content: 'Milestones are sometimes  also called Project Phases',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-13"]',
        content: 'You can specify materials, and add new items – up to you. ',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-14"]',
        content: 'The Summary section of the Project Scope is auto-generated for Projects with multiple Milestones.',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-15"]',
        content: 'Project Summary calculates approx. start and end dates and total cost of each and all milestones, automatically! Nice!',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-16"]',
        content: 'When you are happy with the Project Scope details, click “Create Project Scope”',
        params: {
            placement: 'bottom'
        }
    },
    {
        target: '[data-v-step="ccd-17"]',
        content: 'The Guarantee sends Project Scope Draft to your client for approval. We will notify you once Contract Details approved.',
        params: {
            placement: 'bottom'
        }
    },

];
export default {
    data(){
        return {
            currentStep: 0,
            optionsVueTour: {
                startTimeout: 500,
            },
            steps:[],
            tourCallbacks: {
                onStop: this.onStopTour,
                onPreviousStep: this.myCustomPreviousStepCallback,
                onNextStep: this.myCustomNextStepCallback,
                onStart: this.onStartTour
            },
        }
    },
    methods:{
        getSteps() {
            if (this.user.role === 'homeowner') {
                this.steps = stepsHo;
            } else {
                this.steps = stepsPro;
            }
        },
        changeTypeTour() {
            this.contract_draft.type = 'several';
            this.$store.dispatch('calculateSummary');
        },
        onStartTour(){
        },
        onStopTour() {
            this.$store.commit('cookieTourPush', 'create_project')
        },
        myCustomPreviousStepCallback (currentStep) {
            this.currentStep = currentStep - 1;
        },
        myCustomNextStepCallback (currentStep) {
            this.currentStep = currentStep + 1;
            if(this.currentStep === 1){
                this.changeTypeTour();
            }
        },
    },
    created(){
        this.getSteps();
    },
    mounted(){
        if(window.innerWidth >= 1200 && this.$store.state.first_enter_tour['create_project'] === true){
            this.$tours['tourCreateContract'].start();
        }
    },
}
