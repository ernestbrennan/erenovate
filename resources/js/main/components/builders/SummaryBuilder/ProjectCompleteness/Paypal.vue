<template>
    <v-dialog light max-width="556" v-model="show" content-class="main-dialog__scroll-content scrollbar">
        <div class="main-dialog main-dialog_open">

            <div class="main-dialog__header">
                <img @click="close" class="close-dialog"
                     src="/img/icon/close-modal_gray.svg">
                <h5 class="main-dialog__header-title">
                    <template v-if="user.role === 'homeowner'">
                        Guarantee Project Fee
                    </template>
                    <template v-else>
                        Guarantee Fee Adjustment
                    </template>
                </h5>
            </div>

            <div class="main-dialog__body">
                <template v-if="user.role === 'homeowner'">
                    <p class="main-dialog__p">
                        Great stuff, they project is completed.
                        It appears the total Project price has changed from an initial
                        ${{summary.summary_table.payment_total.estimated_total}}
                        to the revised ${{summary.summary_table.payment_total.final_total}} total cost.
                    </p>
                    <p class="main-dialog__p">
                        You previously paid ${{estimated_total_fee}} as the eRenovate Project Fee.
                    </p>
                    <p class="main-dialog__p">As per the revised price,
                        please pay remaining balance of ${{getFee(price)}} to cover the revised Project Fee;
                        This is required to begin the 88 Day Maintenance Period for the homeowner.
                    </p>
                </template>
                <template v-else>
                    <p class="main-dialog__p">
                        The original Project Price according to the initial Project Scope details was
                        ${{summary.summary_table.payment_total.estimated_total}}
                    </p>
                    <p class="main-dialog__p">
                        The Final Project Price is ${{summary.summary_table.payment_total.final_total}}
                    </p>
                    <p class="main-dialog__p">
                        An adjusted Guarantee Project Fee of ${{getFee(price)}} is due in consideration of the Final
                        Project Price.
                    </p>
                    <p class="main-dialog__p">
                        To activate the client’s 88 Day Maintenance Guarantee Period, please submit payment via PayPal
                        below.
                    </p>
                </template>
            </div>

            <div class="main-dialog__footer">
                <div class="main-dialog__footer-btn-row">
                    <!--<button ref="done" class="main-btn main-btn_full-blue" @click="close">-->
                    <!--DONE pay pal-->
                    <!--</button>-->
                    <div id="paypal-button-container" ref="paypal"></div>

                </div>
            </div>
        </div>
    </v-dialog>
</template>

<script>
    import {paypal_mixin} from '../../../mixins/paypal/paypal'
    import {mapGetters} from 'vuex'

    export default {
        mixins: [paypal_mixin],
        props: {
            value: Boolean,
        },
        computed: {
            show: {
                get() {
                    return this.value
                },
                set(value) {
                    this.$emit('input', value)
                }
            },
            price() {
                return formatPrice(this.summary.summary_table.payment_total.final_total) - formatPrice(this.summary.summary_table.payment_total.estimated_total)
            },
            estimated_total_fee() {
                return this.getFee(formatPrice(this.summary.summary_table.payment_total.estimated_total))
            },

            ...mapGetters(["summary", "user", "guarantee_project"])

        },
        methods: {
            close() {
                this.show = false;
            },
            getPaymentDescription() {
                let project_name = this.guarantee_project.project_name;
                let user_name = this.user.firstname + ' ' + this.user.last_name;
                return `Project ${project_name} - ${user_name}`
            },
            approvePayment(data, details){
                this.$store.state.global_loader = true;

                api.post('payment.summaryFee', {
                    order_id: data.orderID,
                    contract_id: this.summary.contract.id
                })
                    .then(response => {

                        this.$store.state.global_loader = false;

                        if (response.data.response.status === 'COMPLETED') {

                            this.$router.push({
                                name: 'messages',
                                params: {
                                    guarantee_project_id: this.$route.params.guarantee_project_id
                                }
                            });
                        }
                    });
            }
        },
        created(){
            console.log(this.getDescription());
        }
    }
</script>