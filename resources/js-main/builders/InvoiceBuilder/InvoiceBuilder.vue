<template>
  <div
    class="contract-comp__body component-body_scroll scrollbar contract-comp_invoice"
    :style="{ height : componetConteinerHeight}"
  >

    <basic-information :invoice="invoice" :readOnly="readOnly"/>

    <div class="contract-draft__el-box">
      <div class="contract-draft__title-box" @click="toggleBlock">
        <div class="contract-draft__title">Milestone Details</div>
        <img class="contract-draft__curret" :class="{ active: iconToggle }" :src="'/img/icon/caret-icon_gray.svg'">
      </div>

      <div
        class="contract-draft__slide-main-box contract-draft__gray-box_invoice contract-draft__gray-box"
        ref="items_box"
      >

        <works :works="invoice.works" :readOnly="readOnly"/>

        <template v-if="is_file_render">
          <v-tooltip right>
            <label slot="activator" class="contract-draft__input-label">
              File Attachments for Payment Request
              <img src="/img/icon/info-icon_gray.svg" alt="" class="contract-draft__tooltip">
            </label>
            <span>
              Attach milestone related receipts or back-up documents you consider necessary to support your payment request.
            </span>
          </v-tooltip>
        </template>

        <invoice-attachments :readOnly="readOnly" :batches="invoice.batches"/>

        <contract-materials
          :materials="invoice.materials"
          :material_files="invoice.material_files"
          :material_supply_side="invoice.milestone.milestone_content.material_supply_side"
          :readOnly="readOnly"
        />

        <related-milestone :readOnly="readOnly" :milestone="invoice.milestone"/>

      </div>
    </div>

    <summary-box :readOnly="readOnly"></summary-box>

    <!--<template v-if="readOnly">-->
    <!--<confirmation-box :batches="batches"></confirmation-box>-->
    <!--</template>-->
  </div>

</template>
<script>
  import BasicInform from './BasicInformation/BasicInformation'
  import Works from './Works/Works'
  import InvoiceAttach from '@/components/common/FileAttachments'
  import InvoiceMaterials from './Materials/Materials'
  import RelatedMilestone from './RelatedMilestone/RelatedMilestone'
  import Summary from './Summary/Summary'
  import Confirmation from './Confirmation/Confirmation'
  import {ContainerHeight} from '@/components/common/Mixins/builder'

  export default {
    mixins: [ContainerHeight],
    components: {
      'basic-information': BasicInform,
      'works': Works,
      'invoice-attachments': InvoiceAttach,
      'contract-materials': InvoiceMaterials,
      'related-milestone': RelatedMilestone,
      'summary-box': Summary,
      'confirmation-box': Confirmation,

    },
    data() {
      return {
        iconToggle: true,
      }
    },
    props: {
      contentHeight: [Boolean, Number],
      readOnly: Boolean,
      invoice: Object
    },
    computed: {
      is_file_render() {
        if (this.readOnly && this.invoice.batches.length) {
          return true
        } else if (this.readOnly === false) {
          return true
        } else if (this.readOnly && this.invoice.batches.length === 0) {
          return false
        }
      },
    },
    methods: {
      toggleBlock() {
        this.iconToggle = !this.iconToggle
        $(this.$refs.items_box).slideToggle();
      },
    },
  }
</script>
