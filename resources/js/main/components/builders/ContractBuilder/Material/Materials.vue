<template>
    <div>
        <div class="contract-draft__border-title"
             v-if="!readOnly || milestone_content.materials.length || milestone_content.material_files.length">
            List of Materials
        </div>
        <div class="row">

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" v-if="!readOnly">
                <v-tooltip right>
                    <label data-v-step="ccd-13" slot="activator" class="contract-draft__input-label">
                        Materials Supplied By:
                        <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'">
                    </label>
                    <span>
                        Use this section to add and track material updates or Change orders that effect total
                        project price. Once both parties agree to changes, the Total Project Price will reflect cost
                        of Material updates.
                    </span>
                </v-tooltip>
                <div class="toggle-buttons toggle-buttons_mobile-column">
                    <button class="toggle-buttons__el left"
                            :class="{active: milestone_content.material_supply_side === 'homeowner'}"
                            :disabled="readOnly"
                            @click="milestone_content.material_supply_side = 'homeowner'">
                        CLIENT
                    </button>
                    <button class="toggle-buttons__el right"
                            :class="{active: milestone_content.material_supply_side === 'contractor'}"
                            :disabled="readOnly"
                            @click="milestone_content.material_supply_side = 'contractor'">
                        CONTRACTOR
                    </button>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12" v-if="!readOnly" style="display:none !important;">
                <v-tooltip right>
                    <label slot="activator" class="contract-draft__input-label">
                        List Provided Per
                        <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'">
                    </label>
                    <span>If any materials are to be listed (or added), select if they will be listed by the related Milestone or an overall list for the entire project.</span>
                </v-tooltip>
                <div class="toggle-buttons toggle-buttons_mobile-column">
                    <button class="toggle-buttons__el left"
                            :class="{active: milestone_content.materials_provide_on === 'milestone'}"
                            :disabled="readOnly"
                            @click="milestone_content.materials_provide_on = 'milestone'">
                        MILESTONE
                    </button>
                    <button class="toggle-buttons__el right"
                            :class="{active: milestone_content.materials_provide_on === 'contract'}"
                            :disabled="readOnly"
                            @click="milestone_content.materials_provide_on = 'contract'">
                        PROJECT
                    </button>
                </div>
            </div>

        </div>
        <template v-if="milestone_content.materials_provide_on === 'contract'">
            <div class="contract-materials">
                <p class="common-p">
                    We recommend this section to add, agree to and track material updates or changes that
                    may occur during the project (a.k.a. Change Orders). Once both parties agree on changes,
                    the cost of changes will be added to total project price.
                </p>
                <div class="contract-materials__table"
                     v-if="!(!this.milestone_content.materials.length && this.readOnly)">
                    <template v-if="material_table_title">
                        <div class="contract-materials__row contract-materials__row_title">
                            <div class="contract-materials__name">
                                <p>Item Name</p>
                            </div>
                            <div class="contract-materials__quantity">
                                <p>Quantity</p>
                            </div>
                            <div class="contract-materials__link">
                                <p>Product link (optional)</p>
                            </div>

                            <template v-if="milestone_content.material_supply_side === 'contractor'">
                                <div class="contract-materials__price-per-unit">
                                    <p>Price per Unit</p>
                                </div>
                                <div class="contract-materials__total">
                                    <p>Total Per Item</p>
                                </div>
                            </template>

                            <div v-if="!readOnly" class="contract-materials__remove"></div>
                        </div>
                    </template>
                    <div :key="index" v-for="(material, index) in getMaterials">
                        <material :material="material"
                                  :material_files_length="milestone_content.material_files.length"
                                  :readOnly="readOnly"
                                  :index="index"
                                  :milestone_index="milestone_index"
                                  :material_supply_side="milestone_content.material_supply_side"
                                  @totalChange="getSum"
                                  @removeMaterial="removeMaterial"
                        />
                    </div>
                </div>
                <template v-if="!readOnly">
                    <button @click="addMaterial" class="main-btn main-btn_border-blue">ADD MATERIAL ITEM</button>
                </template>
                <template v-if="has_materials_attch_title">
                    <label class="contract-draft__input-label">
                        File Attachments for Materials
                    </label>
                </template>

                <materials-file-attachments v-if="!readOnly || milestone_content.material_files.length"
                                            :readOnly="readOnly"
                                            :material_files="milestone_content.material_files"
                />
            </div>
        </template>
    </div>
</template>
<script>
    import MaterialsFileAttachments from "../../../common/MaterialsFileAttachments";
    import Material from "./Material.vue";

    export default {
        components: {
            'materials-file-attachments': MaterialsFileAttachments,
            'material': Material,
        },
        data() {
            return {
                new_material: {
                    title: '',
                    quantity: 1,
                    link: '',
                    price: 0
                }
            }
        },
        props: [
            'milestone_content',
            'readOnly',
            'milestone_index',
        ],
        watch: {
            'milestone_content.material_supply_side': function () {
                this.milestone_content.materials.forEach((material, index) => {
                    this.milestone_content.materials[index].price = 0;
                });

                this.$store.commit('updateDraftSummary', 'material_cost')
            },
            'milestone_content.materials_provide_on': function () {
                this.$store.commit('updateDraftSummary', 'material_cost')
            }
        },
        computed: {
            has_materials_attch_title() {
                if (this.milestone_content.material_files.length && this.readOnly) {
                    return true
                } else if (this.readOnly === false) {
                    return true
                } else {
                    return false
                }
            },
            material_table_title() {
                return window.innerWidth > 768
            },
            getMaterials() {
                if (!this.milestone_content.materials.length) {
                    this.addMaterial()
                }

                return this.milestone_content.materials
            }
        },
        methods: {
            getSum() {
                let sum = this.milestone_content.materials.reduce((total, item) => {
                    return total + item.quantity * item.price;
                }, 0);
                this.$store.state.totalMaterials = sum;
                return sum
            },
            addMaterial() {
                this.milestone_content.materials.push(JSON.parse(JSON.stringify(this.new_material)));
                this.getSum()
            },

            removeMaterial(index) {
                this.milestone_content.materials.splice(index, 1);
                this.getSum()
            }
        },
    }
</script>
