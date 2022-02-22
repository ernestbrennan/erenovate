<template>
    <div>
        <template v-if="collapseRender">
            <div class="contract-materials__row" :class="{'has-changes' : old_material === undefined, 'flex-start-mod' : materials_has_changes }">
                <div class="contract-materials__name">
                    <input placeholder=""
                           type="text"
                           :disabled="readOnly"
                           class="contract-draft__input"
                           v-model="material.title"
                           key="material_title"
                           @focus="clearErrors('material title')"
                           v-validate="'required'"
                           name="material title"
                           :class="inputsClassList('material title', 'title')"
                    >
                <div v-if="isPreviousChanged('title')" class="old-version-message">
                    Previous value : {{ old_material['title'] }}
                </div>

                </div>
                <div class="contract-materials__quantity">
                    <input placeholder=""
                           type="number"
                           :disabled="readOnly"
                           class="contract-draft__input"
                           v-model="material.quantity"
                           @focus="clearErrors('material quantity')"
                           v-validate="'required'"
                           key="material_quantity"
                           name="material quantity"
                           :class="inputsClassList('material quantity', 'quantity')"
                    >
                        <div v-if="isPreviousChanged('quantity')" class="old-version-message">
                            Previous value : {{ old_material['quantity'] }}
                        </div>
                </div>
                <div class="contract-materials__link">
                    <input placeholder=""
                           type="text"
                           :disabled="readOnly"
                           class="contract-draft__input"
                           :class="{'changed-input': isPreviousChanged('link')}"
                           v-model="material.link">
                           <div v-if="isPreviousChanged('link')" class="old-version-message">
                               Previous value : {{ old_material['link'] }}
                           </div>
                </div>
                <div class="contract-materials__price-per-unit" v-if="material_supply_side === 'contractor'">
                    <input placeholder=""
                           type="text"
                           :disabled="readOnly"
                           class="contract-draft__input"
                           v-model.lazy="material.price"
                           key="material_price"
                           v-money="money"
                           @focus="clearErrors('material price')"
                           v-validate="'required|formatted_price'"
                           name="material price"
                           :class="inputsClassList('material price', 'price')"
                    >
                        <div v-if="isPreviousChanged('price')" class="old-version-message">
                            Previous value : {{ old_material['price'] }}
                        </div>

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

                <div v-if="!readOnly"  class="contract-materials__remove">
                        <img @click="removeMaterial" :src="'/img/icon/stop_gray.svg'">
                </div>
            </div>
        </template>
        <template v-else>
            <div class="material-collapse">
                <div ref="materialHeader"
                     class="material-collapse__header">
                    <div class="material-collapse__title">{{material.title}}</div>
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
                           key="material_title"
                           @focus="clearErrors('material title')"
                           v-validate="'required'"
                           name="material title"
                           :class="inputsClassList('material title', 'title')"
                    >
                        <div v-if="isPreviousChanged('title')" style="margin-top: -25px; margin-bottom: 30px;" class="old-version-message">
                            Previous value : {{ old_material['title'] }}
                        </div>
                    <label class="contract-draft__input-label">Product link</label>
                    <input placeholder=""
                           type="text"
                           :disabled="readOnly"
                           class="contract-draft__input"
                           :class="{'changed-input': isPreviousChanged('link')}"
                           v-model="material.link">
                           <div v-if="isPreviousChanged('link')" style="margin-top: -25px; margin-bottom: 30px;" class="old-version-message">
                               Previous value : {{ old_material['link'] }}
                           </div>

                    <label class="contract-draft__input-label">Quantity</label>
                    <input placeholder=""
                           type="number"
                           :disabled="readOnly"
                           class="contract-draft__input"
                           v-model="material.quantity"
                           v-validate="'required'"
                           @focus="clearErrors('material quantity')"
                           key="material_quantity"
                           name="material quantity"
                           :class="inputsClassList('material quantity', 'quantity')"
                    >
                        <div v-if="isPreviousChanged('quantity')" style="margin-top: -25px; margin-bottom: 30px;" class="old-version-message">
                            Previous value : {{ old_material['quantity'] }}
                        </div>
                    <template v-if="material_supply_side === 'contractor'">
                        <label class="contract-draft__input-label">Price per Unit</label>
                        <input placeholder=""
                               type="text"
                               :disabled="readOnly"
                               class="contract-draft__input"
                               v-model.lazy="material.price"
                               key="material_price"
                               v-money="money"
                               @focus="clearErrors('material price')"
                               v-validate="'required|formatted_price'"
                               name="material price"
                               :class="inputsClassList('material price', 'price')"
                        >
                        <div v-if="isPreviousChanged('price')" style="margin-top: -25px; margin-bottom: 30px;" class="old-version-message">
                            Previous value : {{ old_material['price'] }}
                        </div>
                        <label class="contract-draft__input-label">Total Per Item</label>
                        <input placeholder=""
                               type="text"
                               :disabled="readOnly"
                               class="contract-draft__input"
                               v-model="material_total"
                               disabled
                        >
                    </template>
                    <div v-if="!readOnly" class="material-collapse__btn-row">
                        <button @click="removeMaterial" class="main-btn_border-red main-btn">
                            REMOVE
                        </button>
                    </div>
                </div>
            </div>

        </template>
    </div>
</template>

<script>
    import  money from "../../../mixins/money/money"

    export default {
        mixins: [money],
        props: {
            material: Object,
            index: Number,
            material_supply_side: String,
            readOnly: Boolean,
			materials_old:{
				required: false
			},
            old_material:{
                required: false
            },
        },
        data() {
            return {
                material_total: this.material.quantity * this.material.price || null,
            }
        },
        computed: {
            collapseRender() {
                return window.innerWidth > 600
            },
            materials_has_changes(){
                if(!this.old_material){ return false}
                const keys = Object.keys(this.material)
                for(let i = 0 ; i < keys.length ; i++) {
                    if(this.material[keys[i]] !== this.old_material[keys[i]]){
                        return true
                    }
                }
            },
        },
        watch: {
            'material.quantity': function () {
                this.calculateTotal()
            },
            'material.price': function () {
                this.calculateTotal()
            },
        },
        methods: {
            inputsClassList(name, model) {
                if (this.errors.has(name)) {
                    return 'invalid-input scroll__invalid-input'
                } else if (this.isPreviousChanged(model)) {
                    return 'changed-input'
                }
            },
            isPreviousChanged(model) {
                return this.old_material && this.material[model] !== this.old_material[model]
            },
            parsePrice(price){
                let str = price.toString().split(",").join('');
                return parseFloat(str)
            },
            formatPrice(model){
                model = Number.parseFloat(model);
                let string = model.toString().split('.');
                string[0] = string[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return string.join(".");
            },
            removeMaterial() {
                this.$emit('removeMaterial', this.index)
            },
            calculateTotal() {
                const total = this.material.quantity * this.parsePrice(this.material.price);

                if (!isNaN(total) && total > 0) {
                    this.material_total = this.formatPrice(total);
                } else {
                    this.material_total = null;
                }

                this.$store.commit('updateInvoiceSummary', 'materials_cost');
                this.$store.commit('updateInvoiceSummary', 'total_price')
            },
            clearErrors(){

            }
        },
        created() {
            this.calculateTotal()
        },
        mounted() {
            $(this.$refs.materialHeader).click(function () {
                $(this).toggleClass('active')
                $(this).parent().find('.material-collapse__body').slideToggle()
            });
        },
    }
</script>
<style>
    .has-changes {
        border: 2px solid rgba(45, 156, 219, 0.8);
        padding: 5px;
    }
</style>
