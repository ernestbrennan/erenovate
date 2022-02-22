<template>
    <div>
        <template v-if="collapseRender">
            <div class="list-works-invoice__table-row">
                <div class="list-works-invoice__description list-works-invoice__table-row-el">
                    <input placeholder=""
                           type="text"
                           :disabled="readOnly"
                           v-model="work.title"
                           class="contract-draft__input"
                           data-vv-name="title"
                           @focus="clearErrors('title')"
                           v-validate="'required'"
                           :class="{'invalid-input':  errors.has('title') , 'scroll__invalid-input':  errors.has('title')}"
                    >
                </div>
                <div class="list-works-invoice__quantity list-works-invoice__table-row-el">
                    <input placeholder=""
                           type="number"
                           :disabled="readOnly"
                           v-model="work.quantity"
                           class="contract-draft__input"
                           data-vv-name="quantity"
                           v-validate="'required'"
                           @focus="clearErrors('quantity')"
                           :class="{'invalid-input':  errors.has('quantity') , 'scroll__invalid-input':  errors.has('quantity')}"
                    >
                </div>
                <div class="list-works-invoice__per-units list-works-invoice__table-row-el">
                    <input :disabled="readOnly"
                           v-model.lazy="work.price"
                           type="text"
                           class="contract-draft__input"
                           v-money="money"
                           data-vv-name="price"
                           @focus="clearErrors('price')"
                           v-validate="'required|formatted_price'"
                           :class="{'invalid-input':  errors.has('price') , 'scroll__invalid-input':  errors.has('price')}"
                    >
                </div>
                <div class="list-works-invoice__per-total list-works-invoice__table-row-el">
                    <input placeholder=""
                           type="text"
                           disabled
                           v-model="total"
                           class="contract-draft__input"
                    >
                </div>
                <div v-if="!readOnly"  class="list-works-invoice__remove list-works-invoice__table-row-el">
                    <img @click="removeWork" src="/img/icon/stop_gray.svg">
                </div>
            </div>
        </template>
        <template v-else-if="!collapseRender">
            <div class="material-collapse">
                <div ref="workHeader"
                     class="material-collapse__header">
                    <div class="material-collapse__title">
                        {{ work.title }}
                    </div>
                    <div class="material-collapse__curret">
                        <img src="/img/icon/caret-icon_gray.svg">
                    </div>
                </div>
                <div class="material-collapse__body">
                    <label class="contract-draft__input-label">Description</label>
                    <input type="text"
                           :disabled="readOnly"
                           v-model="work.title"
                           @focus="clearErrors('title')"
                           class="contract-draft__input"
                           data-vv-name="title"
                           v-validate="'required'"
                           :class="{'invalid-input':  errors.has('title') , 'scroll__invalid-input':  errors.has('title')}"

                    >
                    <label class="contract-draft__input-label">Quantity</label>
                    <input type="number"
                           :disabled="readOnly"
                           v-model="work.quantity"
                           class="contract-draft__input"
                           data-vv-name="quantity"
                           @focus="clearErrors('quantity')"
                           v-validate="'required'"
                           :class="{'invalid-input':  errors.has('quantity') , 'scroll__invalid-input':  errors.has('quantity')}"

                    >
                    <label class="contract-draft__input-label">Price per Unit</label>
                    <input type="text"
                           :disabled="readOnly"
                           v-model.lazy="work.price"
                           class="contract-draft__input"
                           data-vv-name="price"
                           v-money="money"
                           @focus="clearErrors('price')"
                           v-validate="'required'"
                           :class="{'invalid-input':  errors.has('price') , 'scroll__invalid-input':  errors.has('price')}"
                    >
                    <label class="contract-draft__input-label">Total Per Item</label>
                    <input placeholder=""
                           type="text"
                           disabled
                           v-model="total"
                           class="contract-draft__input"
                    >
                    <div v-if="!readOnly" class="material-collapse__btn-row">
                        <button @click="removeWork" class="main-btn_border-red main-btn">
                            REMOVE
                        </button>
                    </div>
                </div>
            </div>
        </template>

    </div>
</template>
<script>
    import child_validator from "../../../mixins/validator/base/child_validator"
    import money from "../../../mixins/money/money"

    export default {
        mixins: [child_validator, money],
        props: {
            work: Object,
            index: Number,
            readOnly: Boolean
        },
        computed: {
            collapseRender() {
                return window.innerWidth > 600
            },
            total(){
                let num = this.work.quantity * this.parsePrice(this.work.price);
                this.$store.commit('updateInvoiceSummary', 'works_cost');
                this.$store.commit('updateInvoiceSummary', 'total_price');
                return this.formatPrice(num)
            }
        },
        methods: {
            parsePrice(price){
                let str = price.toString().split(",").join('');
                return parseFloat(str)
            },
            removeWork() {
                this.$emit('removeWork', this.index)
            },
            formatPrice(model){
                model = Number.parseFloat(model);
                let string = model.toString().split('.');
                string[0] = string[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return string.join(".");
            },
        },
        mounted(){
            $(this.$refs.workHeader).click(function () {
                $(this).toggleClass('active')
                $(this).parent().find('.material-collapse__body').slideToggle()
            })
        },
    }
</script>