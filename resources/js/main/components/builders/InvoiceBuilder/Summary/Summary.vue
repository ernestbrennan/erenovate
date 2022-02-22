<template>
    <div class="contract-draft__el-box">
        <div class="contract-draft__title-box" @click="openSection">
            <div class="contract-draft__title">
                Payment Request Summary
            </div>
            <div class="contract-draft__el-controls">
                <img :src="'/img/icon/caret-icon_gray.svg'"
                     class="contract-draft__curret"
                     :class="{active : openstate}">
            </div>
        </div>
        <div class="contract-draft__slide-main-box contract-draft__gray-box_invoice contract-draft__gray-box contract-draft__gray-box_full"
             ref="summary_box">

            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box" data-v-step="ciho-4">
                        <label class="contract-draft__input-label" data-v-step="ci-7">
                            Total of Services, $
                        </label>
                        <input type="text"
                               class="contract-draft__input"
                               v-model="works_cost"
                               :disabled="true">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label">
                            Materials Total, $
                        </label>
                        <input class="contract-draft__input"
                               type="text"
                               v-model="materials_cost"
                               :disabled="true">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label">
                            Taxes Included, $
                        </label>
                        <input class="contract-draft__input"
                               v-model.lazy="invoice.taxes"
                               :name="'invoice_taxes'"
                               :disabled="readOnly"
                               key="invoice_taxes"
                               v-money="money"
                               type="text">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box" data-v-step="ciho-5">
                        <label class="contract-draft__input-label" data-v-step="ci-8">
                            Total Payment Requested, $
                        </label>
                        <input type="text"
                               class="contract-draft__input"
                               v-model="total_price"
                               :disabled="true">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import child_validator from "../../../mixins/validator/base/child_validator"
    import money from "../../../mixins/money/money"

    export default {
        mixins: [child_validator, money],
        data() {
            return {
                openstate: true,
            }
        },
        props: [
            'readOnly',
        ],
        computed: {
            ...mapGetters(['invoice_summary', 'invoice']),
            works_cost(){
                return this.formatPrice(this.invoice_summary.works_cost)
            },
            materials_cost(){
                return this.formatPrice(this.invoice_summary.materials_cost)
            },
            total_price(){
                return this.formatPrice(this.invoice.total_price)
            },
        },
        methods: {
            formatPrice(model){
                model = Number.parseFloat(model);
                let string = model.toString().split('.');
                string[0] = string[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return string.join(".");
            },
            openSection() {
                this.openstate = !this.openstate;
                $(this.$refs.summary_box).slideToggle();
            },
        },
        watch: {
            'invoice.taxes': function () {
                this.$store.commit('updateInvoiceSummary', 'total_price');
            }
        }
    }
</script>