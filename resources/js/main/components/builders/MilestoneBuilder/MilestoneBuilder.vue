<template>
    <div class="contract-comp__body component-body_scroll scrollbar contract-comp_invoice"
        :style="{height: componetConteinerHeight}">

        <basic-information :milestone="milestone" :edited_milestone="edited_milestone"/>

        <div v-if="is_materials_empty || !read_only_milestone" class="contract-draft__el-box  materials-scroll-to ">
            <div class="contract-draft__title-box" @click="toggleBlock">
                <div class="contract-draft__title">Materials</div>
                <!-- <img class="contract-draft__curret" :src="'/img/icon/caret-icon_gray.svg'" :class="{ active: iconToggle }"> -->
            </div>

            <div class="contract-draft__slide-main-box contract-draft__gray-box_invoice contract-draft__gray-box" ref="items_box">

                <milestone-materials :materials="milestone_content.materials"
									 :materials_old="materials_old"
                                     :material_files="milestone_content.material_files"
                                     :materials_files_old="edited_material"
                                     :material_supply_side="milestone_content.material_supply_side"
                                     :readOnly="read_only_milestone" />
            </div>
        </div>
    </div>

</template>
<script>
    import {mapGetters} from 'vuex'

    import commonMaterials from '../../builders/InvoiceBuilder/Materials/Materials'
    import BasicInformation from './BasicInformation'
    import {ContainerHeight} from '../../common/Mixins/builder'

    export default {
        mixins: [ContainerHeight],
        components: {
            'basic-information': BasicInformation,
            'milestone-materials': commonMaterials,
        },
        data() {
            return {
                iconToggle: true,
            }
        },
        props: {
            milestone: Object,
            edited_milestone: Object
        },
        computed: {
            ...mapGetters(["user", "read_only_milestone"]),
			materials_old(){
                return this.edited_milestone ? this.edited_milestone.milestone_content.materials : undefined;
			},
            edited_material(){
                return this.edited_milestone ? this.edited_milestone.milestone_content.material_files : undefined;
            },
            milestone_content () {
                return this.milestone.milestone_content
            },

            is_materials_empty() {
                return this.milestone_content.materials.length || this.milestone_content.material_files.length
            }
        },
        methods: {
            toMessages() {
                this.$router.push({
                    name: 'messages',
                    params: {
                        guarantee_project_id: this.$route.params.guarantee_project_id
                    }
                });
            },
            toggleBlock() {
                this.iconToggle = !this.iconToggle;
                $(this.$refs.items_box).slideToggle();
            },
        },
    }
</script>
