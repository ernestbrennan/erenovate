<template>
  <v-dialog light max-width="556" v-model="show" content-class="main-dialog__scroll-content scrollbar">
    <div class="main-dialog main-dialog_open">
      <div class="main-dialog__header">
        <img @click="close" class="close-dialog" src="/img/icon/close-modal_gray.svg">
        <h5 class="main-dialog__header-title">Submit Guarantee Fee</h5>
      </div>
      <div class="main-dialog__body">
        <p class="main-dialog__p">
          Good news! Both parties accept details of the Project Scope and project is ready to begin.
        </p>
        <p class="main-dialog__p">
          To activate the Guarantee on this project, a Guarantee Project Fee of ${{ getFee(price) }} is now due.
          Once we receive your payment, we’ll notify your client that the Guarantee is activated and project is ready to
          start.
        </p>
        <p class="main-dialog__p">Click the PayPal button below to pay the Guarantee Fee.</p>
      </div>
      <div class="main-dialog__footer">
        <div class="btn-row-paypal main-dialog__footer-btn-row">
          <div id="paypal-button-container" ref="paypal"></div>
        </div>
      </div>
    </div>
  </v-dialog>
</template>

<script>
  import {paypal_mixin} from '@/components/mixins/paypal/paypal'
  import close_dialog from '@/components/mixins/dialog/close_dialog'
  import {payContractFee} from '@/api/payment'

  import {mapGetters} from 'vuex'

  export default {
    mixins: [paypal_mixin, close_dialog],
    props: [
      "contract_draft_id",
      "amount",
    ],
    computed: {
      ...mapGetters(["user", "guarantee_project"]),

      price() {
        return this.amount.toFixed(2)
      }
    },
    methods: {
      formatPrice(model) {
        model = Number.parseFloat(model);
        let string = model.toString().split('.');
        string[0] = string[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return string.join(".");
      },

      getPaymentDescription() {
        let project_name = this.guarantee_project.project_name;
        let user_name = this.user.firstname + ' ' + this.user.lastname;
        return `Project ${project_name} - ${user_name}`
      },

      approvePayment(data, details) {
        this.$store.state.global_loader = true;
        payContractFee(data.orderID, this.contract_draft_id).then(response => {
          this.$store.state.global_loader = false;
          if (response.data.response.status === 'COMPLETED') {
            return this.$router.toProjectMessenger();
          }
        });
      },
    },
  }
</script>
