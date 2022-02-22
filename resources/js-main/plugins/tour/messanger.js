export default {
    data() {
        return {
            currentStep: 0,
            optionsVueTour: {
                startTimeout: 500,
            },
            steps: [],
            stepsPro: [
                {
                    target: '.ms-1-class',
                    content: 'Welcome to Project Scope walk-thru',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '.ms-2-class-header',
                    content: 'You can click Tour anytime to start the walk-thru',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-1"]',
                    content: 'This is your main project navigation',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-2"]',
                    content: 'Messages is a built-in Project Messenger Tool. To be protected by the eRenovate Guarantee, all key project discussions must occur within the Messenger.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-3"]',
                    content: 'NOTES is your Private area to store important project items',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-4"]',
                    content: 'FILE Manager is where you access files uploaded by you or the Client you work with',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-6"]',
                    content: 'CREATE PROJECT SCOPE <br> Click to create the first Project Scope for this Project.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '.msa-7as',
                    content: 'DRAFT HISTORY<br>All events related to the Project are tracked here. The project begins and is covered by the Guarantee once you and client accept details in the Project Scope draft!',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-7"]',
                    content: 'PLEASE NOTE:<br>To be offer clients the eRenovate Guarantee, the projects must use the Project Scope platform on eRenovate.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-8"]',
                    content: 'The built-in Messenger is for all project related discussions',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-10"]',
                    content: 'Message history between you and client is archived here.Project notifications and message are shown by type for your convenience. ',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-12"]',
                    content: 'Messenger lets you upload and send files in PPT/JPG/PNG formats',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-13"]',
                    content: 'Get the project started by typing ‘Hello’, then hit send to ‘break the ice’ as they say.',
                    params: {
                        placement: 'bottom'
                    }
                },
            ],
            stepsHo: [
                {
                    target: '.ms-1-class',
                    content: 'Welcome to Project Scope walk-thru',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '.ms-2-class-header',
                    content: 'You can click Tour anytime to start the walk-thru',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-1"]',
                    content: 'This is your main Project Navigation',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-2"]',
                    content: 'Messages is a built-in Project Messenger Tool.To be protected by the eRenovate Guarantee, all key project discussions must occur within the Messenger.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-3"]',
                    content: 'NOTES is your Private area to store important project items',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-4"]',
                    content: 'FILE Manager is where you access files uploaded by you or the Pro you hired.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-6"]',
                    content: 'CREATE PROJECT SCOPE <br> Click to create the first Project Scope for this Project.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '.msa-7as',
                    content: 'DRAFT HISTORY<br> All events related to the Project are tracked here. The project begins and is covered by the Guarantee once you and the Pro accept details in the Project Scope draft!',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '.messenger-ho-9a',
                    content: 'As a client, you can create a Project Scope, but it is best to wait for the Pro to send it to you .While you can create a Project Scope, we highly recommend waiting for the Pro to send you a Draft.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-7"]',
                    content: 'PLEASE NOTE:<br> To be protected by the eRenovate Guarantee, projects must use this Project Scope platform on eRenovate.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-8"]',
                    content: 'The built-in Messenger is for all project related discussions',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '.ms-10-class',
                    content: 'Message history between you and the Pro is archived here.System notifications and message are also shown here for convenience.',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-12"]',
                    content: 'Messenger lets you upload and send files in PPT/JPG/PNG formats',
                    params: {
                        placement: 'bottom'
                    }
                },
                {
                    target: '[data-v-step="ms-13"]',
                    content: 'You can start  by typing ‘Hello’ and click send to start the discussion.',
                    params: {
                        placement: 'bottom'
                    }
                },
            ],
            messengerCallbacks: {
                onStop: this.onStopTour,
                onPreviousStep: this.myCustomPreviousStepCallback,
                onNextStep: this.myCustomNextStepCallback,
                onStart: this.onStartTour
            },
            is_first_time: this.$store.state.isFirstTime,
            timer_couter: 0,
        }
    },
    methods: {
        getSteps() {
            if (this.user.role === 'homeowner') {
                this.steps = this.stepsHo;
            } else {
                this.steps = this.stepsPro;
            }
        },
        insertArray(arr, index, object) {
            arr.splice(index, 0, object);
        },
        updateStepsHo() {
            if (this.guarantee_project.contract_status === 'signed') {
                this.steps.splice(8, 1);

                this.steps[6].content = 'CURRENT MILESTONE<br>Projects consist of 1 or many Milestones or stages to help keep on track, and are agreed to in the Project Scope Draft stage. Current Milestone shows details of the current or ‘active’ Milestone.';
                const a_step = {
                    target: '.ms-6as',
                    content: 'CURRENT MILESTONE <br>Current Milestones can be edited if any project changes are required. Both parties agree to the edits on the platform, so they are covered by the Guarantee.',
                    params: {placement: 'bottom'}
                };
                const b_step = {
                    target: '.ms-7bas',
                    content: 'NOTIFICATIONS:<br> Key Project notifications are saved in the Messages Tab. Click View Details button, and next steps are clearly provided.',
                    params: {placement: 'bottom'}
                };
                this.insertArray(this.steps, 7, a_step);
                this.insertArray(this.steps, 8, b_step);

            } else if (this.guarantee_project.contract_status === 'finished' || this.guarantee_project.contract_status === 'completed') {
                this.steps.splice(8, 1);
                this.steps[6].content = 'PROJECT SUMMARY <br> A convenient overview from start to finish. All key events along the way are mapped, and it remembers the big and finer details for you. Another BIG benefit of Project Scope on the eRenovate Guarantee platform.';
                const b_step = {
                    target: '.ms-7bas',
                    content: 'NOTIFICATIONS:<br> Key Project notifications are saved in the Messages Tab. Click View Details button, and next steps are clearly provided.',
                    params: {placement: 'bottom'}
                };
                this.insertArray(this.steps, 7, b_step);
            } else if (this.guarantee_project.has_contract_draft) {
                this.steps.splice(8, 1);
                const a_step = {
                    target: '.ms-6as',
                    content: 'PROJECT SCOPE DRAFT<br>As the client, you can start creating the 1st Scope Draft.  It’s most common for the Pro to create the 1st Draft for you to review, accept and/or provide feedback.',
                    params: {placement: 'bottom'}
                };
                this.insertArray(this.steps, 7, a_step);
                this.steps[6].content = 'PROJECT SCOPE DRAFT<br>The Project Scope easily outlines key Project details to be agreed on, as per the signed contract. The Project Draft starts the process of defining those details.';
                const b_step = {
                    target: '.ms-7bas',
                    content: 'NOTIFICATIONS:<br> Key Project notifications are saved in the Messages Tab. Click View Details button, and next steps are clearly provided.',
                    params: {placement: 'bottom'}
                };
                this.insertArray(this.steps, 8, b_step);
            }

        },
        updateStepsPro() {
            if (this.guarantee_project.contract_status === 'signed') {
                this.steps[6].content = 'CURRENT MILESTONE<br>Projects consist of 1 or many Milestones or stages, and Milestones are agreed to via the Project Scope Draft. Current Milestone shows details of current or ‘active’ Milestone.';
                const a_step = {
                    target: '.ms-6as',
                    content: 'CURRENT MILESTONE<br>Current Milestones can be edited if any project changes are required. Both parties agree to the edits on the platform, so they are covered by the Guarantee.',
                    params: {placement: 'bottom'}
                };
                const b_step = {
                    target: '.ms-7bas',
                    content: 'NOTIFICATIONS:<br> Key Project notifications are saved in the Messages Tab. Click View Details button, and next steps are clearly provided.',
                    params: {placement: 'bottom'}
                };
                this.insertArray(this.steps, 7, a_step);
                this.insertArray(this.steps, 8, b_step);

            } else if (this.guarantee_project.contract_status === 'finished' || this.guarantee_project.contract_status === 'completed') {
                this.steps[6].content = 'PROJECT SUMMARY<br> A convenient overview from start to finish. All key events are recorded. It remembers both big and finer details so you don’t have to. Just another BIG benefit of using Project Scope on the eRenovate Guarantee platform.';
                const b_step = {
                    target: '.ms-7bas',
                    content: 'NOTIFICATIONS:<br> Key Project notifications are saved in the Messages Tab. Click View Details button, and next steps are clearly provided.',
                    params: {placement: 'bottom'}
                };
                this.insertArray(this.steps, 8, b_step);
            } else if (this.guarantee_project.has_contract_draft) {
                this.steps[6].content = 'PROJECT SCOPE DRAFT<br>The Project Scope easily outlines key Project details to be agreed on, as per the signed contract. The Project Draft  starts the process of defining those details.';
                const a_step = {
                    target: '.ms-6as',
                    content: 'PROJECT SCOPE DRAFT<br> As the PRO you can start creating the 1st Scope Draft. It’s most common for the Pro to create the 1st Draft.',
                    params: {placement: 'bottom'}
                };
                this.insertArray(this.steps, 7, a_step);
                const b_step = {
                    target: '.ms-7bas',
                    content: 'NOTIFICATIONS:<br> Key Project notifications are saved in the Messages Tab. Click View Details button, and next steps are clearly provided.',
                    params: {placement: 'bottom'}
                };
                this.insertArray(this.steps, 8, b_step);
            }
        },
        onStartTour() {
            this.currentStep = 0;
        }
        ,
        onStopTour() {
            this.$store.commit('cookieTourPush', 'messenger')
        }
        ,
        myCustomPreviousStepCallback(currentStep) {
            this.currentStep = currentStep - 1;
            if(this.currentStep === 1){
                const timer = setTimeout(()=>{
                    $('.v-tour .v-step').css('position','fixed');
                    clearTimeout(timer);

                },50)

            }
        },
        myCustomNextStepCallback(currentStep) {
            this.currentStep = currentStep + 1;
            if(this.currentStep === 1){
                const timer = setTimeout(()=>{
                    $('.v-tour .v-step').css('position','fixed');
                    clearTimeout(timer);

                },50)
            }
        },
        updateAllSteps() {
            if (this.user.role === 'homeowner') {
                this.updateStepsHo();
            } else {
                this.updateStepsPro()
            }
        },
        startInMount(){
            if(this.$route.name !== 'messages'){ return false }

            if(this.$store.state.global_loader === false){
                this.$tours['tourMessenger'].start();
            } else {
                const timer = setTimeout(() => {
                    if(this.timer_couter < 20){
                        this.startInMount()
                    }
                    this.timer_couter++;
                    clearTimeout(timer)
                }, 1500)
            }
        },

    },
    created() {
        this.getSteps()
    },
    mounted() {
        if (window.innerWidth >= 1200 && this.$store.state.first_enter_tour['messenger']) {
            this.startInMount()
        }
    },
}
