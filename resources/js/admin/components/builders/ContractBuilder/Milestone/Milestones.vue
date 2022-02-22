<template>
    <div class="contact-draft__milestones-list">
            <div :key="milestone.id" v-for="(milestone, index) in milestones" class="contract-draft__el-box">
                <milestone :open="openMod(index)"
                           :milestone="milestone"
                           :index="index"
                           :readOnly="readOnlyMilestones"
                />
            </div>
        <contract-summary :read_only="readOnly"/>
    </div>
</template>
<script>
    import Milestone from "./Milestone.vue"
    import Summary from "../Summary/Summary.vue"

    export default {
        components: {
            'milestone': Milestone,
            'contract-summary': Summary,
        },
        props: [
            'milestones',
            'readOnly',
        ],
        data() {
            return {
                new_milestone: {
                    status: 'pending',
                    milestone_content: {
                        title: '',
                        description: '',
                        price: 0,
                        start_date: null,
                        end_date: null,
                        status: 'pending',
                        material_supply_side: 'contractor',
                        materials_provide_on: 'contract',
                        batches: [],
                        materials: [],
                        material_files: [],
                    },
                }
            }
        },
        computed: {
            desktopOnly() {
                return window.innerWidth > 1024
            },
            readOnlyMilestones() {
                if (this.$route.name === 'edited-milestone') {

                    let has_edited = false;
                    this.milestones.forEach((milestone) => {
                        if (milestone.milestone_content.status === 'pending' || milestone.milestone_content.status === 'completed') {
                            has_edited =  true
                        }
                    });

                    return has_edited;
                }

                return this.readOnly;
            }
        },
        methods: {
            openMod: function (index) {
                return this.milestones.length - 1 === index
            },
        },
    }
</script>
