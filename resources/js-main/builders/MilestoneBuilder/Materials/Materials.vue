<template>
  <div>
    <div class="contract-draft__border-title" data-v-step="ci-5" v-if="materials.length || !this.readOnly">
      List of Materials
      <v-tooltip right>
        <img class="contract-draft__tooltip" src="/img/icon/info-icon_gray.svg" slot="activator">
        <span>
          This section provides list of the materials that may need to be identified for completion of this milestone.
        </span>
      </v-tooltip>
    </div>

    <div class="contract-materials" data-v-step="ciho-3" v-if="materials.length || !this.readOnly">
      <div class="contract-materials__table" data-v-step="ci-6">
        <template v-if="this.materialTableTitle">
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
            <div class="contract-materials__price-per-unit"
                 v-if="material_supply_side === 'contractor'">
              <p>Price per Unit</p>
            </div>
            <div class="contract-materials__total" v-if="material_supply_side === 'contractor'">
              <p>Total Per Item</p>
            </div>
            <div v-if="!readOnly" class="contract-materials__remove"></div>
          </div>
        </template>

        <div :key="index" v-for="(material, index) in getMaterials">
          <material
            :material="material"
            :old_material="f_materials_old === false ? false : f_materials_old[index]"
            :readOnly="readOnly"
            :index="index"
            :material_supply_side="material_supply_side"
            @totalChange="getSum"
            @removeMaterial="removeMaterial"
          />
        </div>
      </div>

      <template v-if="!readOnly">
        <button @click="addMaterial" class="main-btn main-btn_border-blue">ADD MATERIAL ITEM</button>
      </template>

      <template v-if="!readOnly || material_files.length">
        <label
          class="contract-draft__input-label">
          File Attachments for Materials
        </label>
      </template>

      <materials-file-attachments
        v-if="!readOnly || material_files.length"
        :material_files="material_files"
        :has_changed_material_files="has_changed_material_files"
        :readOnly="readOnly"
      />
    </div>
  </div>
</template>
<script>
  import Material from "./Material.vue";
  import MaterialsFileAttachments from '@/components/common/MaterialsFileAttachments'

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
      'materials',
      'materials_old',
      'material_files',
      'materials_files_old',
      'readOnly',
      'material_supply_side',
    ],
    computed: {
      has_changed_material_files() {
        if (this.materials_files_old !== undefined) {
          return this.material_files.length !== this.materials_files_old.length ||
            this.materials_files_old.some((material, index) => {
              return material.name !== this.material_files[index].name
            })
        }
        return false
      },
      materialTableTitle() {
        return window.innerWidth > 768;
      },
      getMaterials() {
        if (!this.materials.length && !this.readOnly) {
          this.addMaterial()
        }
        return this.materials
      },
      f_materials_old() {
        return this.materials_old ? this.materials_old : false
      }

    },
    methods: {
      getSum() {
        let sum = this.materials.reduce((total, item) => {
          return total + item.quantity * item.price;
        }, 0);
        this.$store.state.totalMaterials = sum;
        return sum
      },
      addMaterial() {
        this.materials.push(JSON.parse(JSON.stringify(this.new_material)));
        this.getSum()
      },
      removeMaterial(index) {
        this.materials.splice(index, 1);
        this.getSum()
      },
    }
  }
</script>
