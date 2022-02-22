<template>
    <div class="summary-block">
        <h3 class="summary-block__border-title">Project Payments</h3>
        <div class="milestone-table">
            <div class="milestone-table__row hidden-sm-and-down">
                <div class="milestone-table__work milestone-table__row-el">
                    <h5 class="milestone-table__title">Work Description</h5>
                </div>
                <div class="milestone-table__est-price milestone-table__row-el">
                    <h5 class="milestone-table__title">Estimated Price</h5>
                </div>
                <div class="milestone-table__fin-price milestone-table__row-el">
                    <h5 class="milestone-table__title">Final Price</h5>
                </div>
            </div>

            <div :key="index" v-for="(payment, index) in payments">

                <payment :payment="payment"/>

            </div>

            <div class="milestone-table__row milestone-table__pr-row">
                <div class="milestone-table__work milestone-table__row-el">
                    <div class="milestone-table__pr-title">Work Price</div>
                    <div class="milestone-table__price">$ {{payment_total.final_work}}</div>
                </div>
                <div class="milestone-table__est-price milestone-table__row-el">
                    <div class="milestone-table__pr-title">Material price</div>
                    <div class="milestone-table__price">$ {{payment_total.final_material}}</div>
                </div>
                <div class="milestone-table__fin-price milestone-table__row-el">
                    <div class="milestone-table__pr-title">Total price</div>
                    <div class="milestone-table__price">$ {{payment_total.final_total}}</div>
                </div>
            </div>

            <template v-if="hasPriceDiscrepancy">
                <div class="admin__change-price-box">
                    <div class="milestone-table__pr-title">New Total Price</div>
                    <div class="row">
                        <div class="col-lg-7 col-md-7 col-sm-7 col-12">
                            <input type="text"
                                   class="contract-draft__input"
                                   v-model.lazy="payment_total.final_total"
                                   v-validate="'required'"
                                   data-vv-name="admin_price"
                                   v-money="money"
                                   :class="{'invalid-input scroll__invalid-input':  errors.first('admin_price')}"
                            >

                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-12">
                            <button @click="dialog = true" class="main-btn main-btn_border-blue">
                                Save
                            </button>
                        </div>
                    </div>
                </div>

                <dialog-confirm
                    v-model="dialog"
                    :content="content"
                    reverse="true"
                    @submit="changePrice"
                ></dialog-confirm>
            </template>

        </div>
    </div>
</template>

<script>
    import {money} from "../../../common/Mixins/money";
    import Payment from './Payment'
    import ConfirmDialog from '../../../common/elements/DialogConfirm'

    export default {
        data() {
            return {
                dialog: false,
                content: {
                    title: 'Change price',
                    text: `<p class="main-dialog__p">Are you sure you want to change this price?</p>`,
                    submitBtn: 'SAVE',
                    cancelBtn: 'CANCEL',
                },
            }
        },
        created() {
        },
        mixins: [money],
        components: {
            'payment': Payment,
            'dialog-confirm': ConfirmDialog,
        },
        props: [
            'payments',
            'payment_total',
            'summary_id',
            'contract_status'
        ],
        computed: {
            hasPriceDiscrepancy() {
                let issues = this.$store.state.guarantee_project.issues;
                for (let i = 0 ; i < issues.length; i++) {
                    if (issues[i].type == 'price_modification') {
                        return true;
                    }
                };
                return false;
            }
        },
        methods: {
            changePrice() {
                const price = this.payment_total.final_total;

                api
                    .post('summary.changeTotal', {
                        summary_id: this.summary_id,
                        changed_total: price
                    });
            },
        },
    }
</script>
