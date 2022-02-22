<template>
    <div>
        <div class="contract-draft__border-title"
             v-if="!readOnly || milestone_content.materials.length || milestone_content.material_files.length">
            Materials
        </div>
        <template v-if="milestone_content.materials_provide_on === 'contract'">
            <div class="contract-materials">
                <p class="common-p">
                    We strongly recommend you use this platform to keep track of supplies and materials used for this project. This will provide a clear overview of materials.
                    (You are also allowed to upload receipts of materials)
                </p>
                <div class="contract-materials__table" v-if="!(!this.milestone_content.materials.length && this.readOnly)">
                    <template v-if="materialTableTitle">
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
                                <div class="contract-materials__total" >
                                    <p>Total Per Item</p>
                                </div>
                            </template>
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
                        />
                    </div>
                </div>
                <template v-if="hasMaterialsAttchTitle">
                    <v-tooltip right>
                        <label slot="activator"
                               class="contract-draft__input-label">
                            Materials File Attachments
                            <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'">
                        </label>
                        <span>Upload receipts for materials and other important supporting documents required to best identify the materials required to complete the project</span>
                    </v-tooltip>
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
    import Material from "./Material.vue";
    import MaterialsFileAttachments from "../../../common/MaterialsFileAttachments.vue";

    export default {
        components: {
            'material': Material,
            'materials-file-attachments': MaterialsFileAttachments
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
            hasMaterialsAttchTitle(){
                if(this.milestone_content.material_files.length && this.readOnly){
                    return true
                } else if (this.readOnly === false){
                    return true
                } else {
                    return false
                }
            },
            materialTableTitle() {
                return window.innerWidth > 768
            },
            
            getMaterials(){
                if (!this.milestone_content.materials.length) {
                    this.addMaterial()
                }

                return this.milestone_content.materials
            }

        },
        methods: {
            getSum(){
                let sum = this.milestone_content.materials.reduce((total, item) => {
                    return total + item.quantity * item.price;
                }, 0);
                this.$store.state.totalMaterials = sum;
                return sum
            },
        },
    }
</script>
