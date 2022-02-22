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
          target: '.sum-tour-3',
          content: 'Project Timeline:<br> The timeline remembers the ‘what/when’ of key project events so you don’t have to.  Click View for more details.',
          params: {
            placement: 'bottom'
          }
        },
        {
          target: '.sum-tour-5',
          content: 'Project Payments:<br>A summary of all Project Payments.',
          params: {
            placement: 'top'
          }
        },
        {
          target: '.sum-tour-6',
          content: 'Project Payments <br> Description of Payments identifies all Milestone Payments, including Work (labour) and Materials (if you separated them)',
          params: {
            placement: 'top'
          }
        },
        {
          target: '.sum-tour-7',
          content: 'Estimate Price:<br> The starting price of the Milestone as agreed to in the original Project Scope Draft.',
          params: {
            placement: 'top'
          }
        },
        {
          target: '.sum-tour-8',
          content: 'Final Price:<br> As a Milestone starts, and changes are agreed to by you and the client, the Final Price reflects that.',
          params: {
            placement: 'top'
          }
        },
        {
          target: '.sum-tour-9',
          content: 'Services Price<br> Total cost or Price of Services',
          params: {
            placement: 'top'
          }
        },
        {
          target: '.sum-tour-10',
          content: 'Materials Price<br> If and only if you separate Material pricing, it will be identified in this field.',
          params: {
            placement: 'top'
          }
        },
        {
          target: '.sum-tour-11',
          content: 'Total Price<br> The Total Project price which includes Services Price and Materials Price.',
          params: {
            placement: 'top'
          }
        },
      ],
      stepsHo: [
        {
          target: '.sum-tour-1',
          content: 'Project Status:<br> Project Completed Approval Pending indicates that eRenovate is waiting for you to Confirm the Project is completed.',
          params: {
            placement: 'bottom'
          }
        },
        {
          target: '.sum-tour-2',
          content: 'Project Completion<br> Once you click Complete Project button, we link you to the Confirm Project Completion section with some key instructions.',
          params: {
            placement: 'right'
          }
        },
        {
          target: '.sum-tour-3',
          content: 'Project Timeline:<br> The timeline remembers the ‘what/when’ of key project events so you don’t have to.  Click View for more details.',
          params: {
            placement: 'bottom'
          }
        },
        {
          target: '.sum-tour-5',
          content: 'Project Payments:<br>A summary of all Project Payments.',
          params: {
            placement: 'top'
          }
        },
        {
          target: '.sum-tour-6',
          content: 'Description of Payments identifies all Milestone Payments, including Work (labour) and Materials (if separated)',
          params: {
            placement: 'top'
          }
        },
        {
          target: '.sum-tour-7',
          content: 'Estimated Price:<br>The starting price of the Milestone as per the original Project Scope Draft.',
          params: {
            placement: 'top'
          }
        },
        {
          target: '.sum-tour-8',
          content: 'Final Price:<br> As a Milestone begins, and changes are agreed to by you and the Pro, the Final Price reflects that.',
          params: {
            placement: 'top'
          }
        }, {
          target: '.sum-tour-9',
          content: 'Services Price<br> Total Price (or Cost ) of Services',
          params: {
            placement: 'top'
          }
        },
        {
          target: '.sum-tour-10',
          content: 'Materials Price<br> If and only if you separate Material pricing, it appears in this field.',
          params: {
            placement: 'top'
          }
        },
        {
          target: '.sum-tour-11',
          content: 'Total Price<br> The Total Project price combining the Services Price and Materials Price.',
          params: {
            placement: 'top'
          }
        },
        {
          target: '.sum-tour-12s',
          content: 'Submit a Review:<br>To ensure we provide you and our community with the best Pros, we ask you to leave feedback on your experience with the Pro you hired. The information is not shared with the Pro.',
          params: {
            placement: 'top'
          }
        },
        {
          target: '.sum-tour-13s',
          content: 'Confirm Final Project Price:<br>Simply check this box if the final project price matches the price in the original Project Scope.',
          params: {
            placement: 'bottom'
          }
        },
        {
          target: '.sum-tour-14s',
          content: 'Greater Final Price:<br>If the final Project Price is greater than the original Project Scope price, click this button to advise eRenovate, and to make sure the Guarantee covers the entire project..',
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
    scrollInBox(target_value) {
      let target_scroll = 0;
      let scroll_body = $(".component-body_scroll");
      if (typeof target_value === 'string') {
        target_scroll = $(target_value).offset().top
      } else if (typeof target_value === 'number') {
        target_scroll = $(".component-body_scroll").scrollTop() + target_value;
      }
      scroll_body.animate({
        scrollTop: target_scroll
      }, 100)
    },
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
    onStartTour() {
      this.currentStep = 0;
      
    },
    onStopTour() {
      this.$store.commit('cookieTourPush', 'summary')
    },
    myCustomPreviousStepCallback(currentStep) {
      this.currentStep = currentStep - 1;
      if (this.currentStep === 11 || this.currentStep === 12) {
        this.scrollInBox(-10)
      } else if (this.currentStep === 8 || this.currentStep === 9) {
        this.scrollInBox(-10)
      }
    },
    myCustomNextStepCallback(currentStep) {
      this.currentStep = currentStep + 1;
      if (this.currentStep === 11 || this.currentStep === 12) {
        this.scrollInBox(-10)
      } else if (this.currentStep === 9) {
        this.scrollInBox(-10)
      }
    },
    updateStepsHo() {
      if (this.needConfirmComplete === false) {
        this.steps.splice(0, 2);
        this.steps.splice(this.steps.length - 1, 1);
        this.steps[8].content = 'Welcome to your 88 Day Guarantee!<br> You are required to report issues via this platform Interface, so we can notify the Pro immediately to resolve the issue. Your issues and their status will be saved and displayed here.';
        this.steps[9].content = 'REPORT AN ISSUE<br> Click the button to Report and Submit a Workmanship issue to notify the Pro.';
      } else if (this.summary_contract.status === 'finished' && this.summary_contract.ho_confirm_complete) {
        this.steps.splice(11, 2);
        this.steps[10].content = 'Project Completed Pending<br>Once you and the Pro indicate Project is Completed, an eRenovate Project Advisor will review the final Project Summary. Once approved, your 88 Day Workmanship Guarantee begins.';
      }
    },
    updateStepsPro() {
      if (this.summary_contract.status === 'finished' && (this.summary_contract.ho_confirm_complete || this.user.role === 'contractor')) {
        const tick_a = {
          target: '.sum-tour-ho3s',
          content: 'Project Completed:<br>Once you change Project Status to Completed, client must also agree by clicking Project Completed. Once both sides agree, this activates the 88 Day Workmanship Guarantee.',
          params: {
            placement: 'top'
          }
        }
        this.steps.push(tick_a);
        if (this.summary_contract.ho_confirm_complete) {
          const tick_b = {
            target: '.sum-tour-ho4s',
            content: 'Possible 2nd Project Fee:<br> If client indicates a final Project Price greater than the price in the original Project Scope Draft, a 2nd Project Guarantee Fee will be invoiced to ensure the eRenovate Guarantee covers all the project elements as completed.',
            params: {
              placement: 'top'
            }
          }
          this.steps.push(tick_b)
        }
      } else if (this.summary_contract.status === 'completed' && this.needConfirmComplete === false) {
        const tick_a = {
          target: '.sum-tour-ho2s',
          content: 'Workmanship Issue Interface:<br>Clients are required to report issues via the platform, which will be shown here. You and eRenovate are notified so to reply to and resolve the issues in a timely manner.',
          params: {
            placement: 'top'
          }
        }
        this.steps.push(tick_a);
      }
    }
  },
  created() {
    this.getSteps()
  },
  mounted() {
    if (this.user.role === 'homeowner') {
      this.updateStepsHo();
    } else {
      this.updateStepsPro();
    }
    if (window.innerWidth >= 1200 && this.$store.state.first_enter_tour['summary']) {
      this.$tours['tourSummary'].start();
    }
  },
  beforeUpdate() {
  },
  beforeDestroy() {
  },
}
