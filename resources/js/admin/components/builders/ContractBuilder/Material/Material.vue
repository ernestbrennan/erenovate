<template>
    <div>
        <template v-if="collapseRender">
            <div class="contract-materials__row">
                <div class="contract-materials__name">
                    <input placeholder=""
                           type="text"
                           :disabled="readOnly"
                           class="contract-draft__input"
                           v-model="material.title"
                           key="material_title"
                    >

                </div>
                <div class="contract-materials__quantity">
                    <input type="number"
                           :disabled="readOnly"
                           class="contract-draft__input"
                           v-model="material.quantity"
                    >
                </div>
                <div class="contract-materials__link">
                    <input placeholder=""
                           type="text"
                           :disabled="readOnly"
                           class="contract-draft__input"
                           v-model="material.link">
                </div>
                <div class="contract-materials__price-per-unit" v-if="material_supply_side === 'contractor'">
                    <input :disabled="readOnly"
                           class="contract-draft__input"
                           v-model.lazy="material.price"
                           key="material_price"
                           v-money="money"
                    >
                </div>
                <div class="contract-materials__total" v-if="material_supply_side === 'contractor'">
                    <input placeholder=""
                           type="text"
                           :disabled="readOnly"
                           class="contract-draft__input"
                           v-model="material_total"
                           disabled
                    >
                </div>
            </div>
        </template>
        <template v-else>
            <div class="material-collapse">
                <div ref="materialHeader"
                     class="material-collapse__header">
                    <div class="material-collapse__title">{{material_title}}</div>
                    <div class="material-collapse__curret">
                        <img src="/img/icon/caret-icon_gray.svg">
                    </div>
                </div>
                <div class="material-collapse__body">
                    <label class="contract-draft__input-label">Item Name</label>
                    <input placeholder=""
                           type="text"
                           :disabled="readOnly"
                           class="contract-draft__input"
                           v-model="material.title"
                    >
                    <label class="contract-draft__input-label">Product link</label>
                    <input placeholder=""
                           type="text"
                           :disabled="readOnly"
                           class="contract-draft__input"
                           v-model="material.link">

                    <label class="contract-draft__input-label">Quantity</label>
                    <input placeholder=""
                           type="number"
                           :disabled="readOnly"
                           class="contract-draft__input"
                           v-model="material.quantity"
                    >
                    <template v-if="material_supply_side === 'contractor'">
                        <label class="contract-draft__input-label">Price per Unit</label>
                        <input :disabled="readOnly"
                               class="contract-draft__input"
                               v-model.lazy="material.price"
                               v-money="money"
                               key="material_price"
                        >
                        <label class="contract-draft__input-label">Total Per Item</label>
                        <input placeholder=""
                               type="text"
                               :disabled="readOnly"
                               class="contract-draft__input"
                               v-model="material_total"
                               disabled
                        >
                    </template>
                </div>
            </div>

        </template>
    </div>
</template>

<script>
    import {money} from '../../../common/Mixins/money'

    export default {
        mixins: [money],
        props: {
            material: Object,
            index: Number,
            milestone_index: Number,
            material_supply_side: String,
            readOnly: Boolean,
            material_files_length: Number,
        },
        data() {
            return {
                total: this.material.quantity * this.material.price || null,

            }
        },
        computed: {
            material_total() {
                const total = this.material.quantity * this.parsePrice(this.material.price);
                if (!isNaN(total) && total > 0) {
                    return this.formatPrice(total);
                } else {
                    return null;
                }
            },
            material_title() {
                if (this.material.title.length > 20) {
                    return this.material.title.substring(0, 20) + '...'
                }
                return this.material.title
            },
            collapseRender() {
                return window.innerWidth > 600
            }
        },
        methods: {
            parsePrice(price) {
                let str = price.toString().split(",").join('');
                return parseFloat(str)
            },
            formatPrice(model) {
                model = Number.parseFloat(model);
                let string = model.toString().split('.');
                string[0] = string[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return string.join(".");
            },
            calculateTotal() {
                const total = this.material.quantity * this.material.price;
                if (!isNaN(total) && total > 0) {
                    this.total = total;
                } else {
                    this.total = null;
                }
            },
            emitTotal() {
                this.$emit('totalChange', this.total)
            },

        },

        watch: {
            'material_total': function () {
                this.$store.commit('updateDraftSummary', 'material_cost')
                // this.$store.commit('updateDraftSummary', 'total_project_price')
            },
        },
        created() {
        },
        mounted() {
            $(this.$refs.materialHeader).click(function () {
                $(this).toggleClass('active')
                $(this).parent().find('.material-collapse__body').slideToggle()
            })
        },
        beforeDestroy() {
        },

    }
</script>

<style>

</style>
