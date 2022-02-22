<template>
    <div class="contract-draft__el-box">
        <div class="contract-draft__title-box">
            <v-tooltip right>
                <div slot="activator" class="contract-draft__title" @click="openSection">
                    Project Summary
                    <img src="/img/icon/info-icon_gray.svg" class="contract-draft__tooltip">
                </div>
                <span>
                    The project summary is a combined view of<br>
                    the project, and it’s Milestones.The Cost <br>
                    of Services and Cost of Materials is the total of <br>
                    the Milestones combined,and can be used <br>
                    to keep track of your Total Project Price <br>
                    versus the intitial starting Project Cost.
                </span>
            </v-tooltip>
            <div class="contract-draft__el-controls">
                <img :src="'/img/icon/caret-icon_gray.svg'" class="contract-draft__curret"
                     :class="{active : openstate}">
            </div>
        </div>
        <div ref="summary_box"
             class="contract-draft__slide-main-box contract-draft__gray-box" :class="classFull">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12" data-v-step="ccd-14">
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label">
                            Approx. Start Date
                            <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'">
                        </label>
                        <div class="contract-draft__input_dropdown">
                            <input class="contract-draft__input"
                                   placeholder="MM/DD/YY"
                                   v-model="draft_summary.start_date"
                                   :disabled="true"
                                   type="text">
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12" data-v-step="ccd-15">
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label">
                            Approx. End Date
                            <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'">
                        </label>
                        <div class="contract-draft__input_dropdown">
                            <input placeholder="MM/DD/YY"
                                   class="contract-draft__input"
                                   :disabled="true"
                                   v-model="draft_summary.end_date"
                                   type="text">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label">
                            Cost of Services, CAD
                            <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'">
                        </label>
                        <input placeholder="Enter Contract Price Here"
                               class="contract-draft__input"
                               v-model.lazy="draft_summary.service_cost"
                               :disabled="true"
                               v-money="money"
                               type="text">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label">
                            Cost of Materials, CAD
                            <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'">
                        </label>
                        <input placeholder="Enter Contract Price Here"
                               class="contract-draft__input"
                               v-model.lazy="draft_summary.material_cost"
                               :disabled="true"
                               v-money="money"
                               type="text"
                        >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label">
                            Total project price
                            <img class="contract-draft__tooltip" src="/img/icon/info-icon_gray.svg">
                        </label>
                        <input placeholder="Enter Contract Price Here"
                               class="contract-draft__input"
                               v-model.lazy="total"
                               :disabled="true"
                               type="text"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import {money} from "../../../common/Mixins/money";


    export default {
        mixins: [money],
        data() {
            return {
                openstate: true,
                total: '0.00'
            }
        },
        computed: {
            getTotalPrice: {
                get() {
                    return this.parsePrice(this.draft_summary.service_cost) + this.parsePrice(this.draft_summary.material_cost)
                },
                set(value) {
                    value = this.parsePrice(this.draft_summary.service_cost) + this.parsePrice(this.draft_summary.material_cost);
                    return value;
                },
            },
            classFull() {
                if (this.$route.name !== 'contract-signed') {
                    return 'contract-draft__gray-box_full'
                }
                return false;
            },
            ...mapGetters(['draft_summary'])
        },
        watch:{
            'draft_summary.material_cost': function(value) {
                this.setTotal()
            },
            'draft_summary.service_cost': function (value){
                this.setTotal()
            },
        },
        methods: {
            setTotal(){
                let total = this.parsePrice(this.draft_summary.service_cost) + this.parsePrice(this.draft_summary.material_cost);
                this.total = this.currencyFormat(total);
            },
            currencyFormat(num) {
                return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            },
            parsePrice(price) {
                let str = price.toString().split(",").join('');
                str = parseFloat(str);
                return str;
            },
            openSection() {
                this.openstate = !this.openstate;
                $(this.$refs.summary_box).slideToggle();
            }
            ,
        },
        mounted(){
            this.setTotal()
        },
    }
</script>
