<template>
    <div class="contract-draft__el-box">
        <div class="contract-draft__title-box">
            <div class="contract-draft__title" @click="toggleOpen">
                <span :class="{'completed': milestone.status === 'completed'}" class="milestone-title-builder">{{ milestone_title }}</span>
                <span class="hidden-sm-and-down">Milestone {{ index + 1 }}</span>
                <span class="milestone-complete-label" v-if="milestone.status === 'completed'">Completed</span>
            </div>
            <div class="contract-draft__el-controls">

                <img :src="'/img/icon/caret-icon_gray.svg'"
                     class="contract-draft__curret"
                     :class="{active: openstate}"
                     @click="toggleOpen">
            </div>
        </div>

        <div ref="milestone_box"
             class="contract-draft__slide-main-box contract-draft__gray-box" :class="classFull">
            <div class="contract-draft__input-box">
                <v-tooltip right>
                    <label class="contract-draft__input-label"
                           slot="activator">
                        Milestone Title
                        <img class="contract-draft__tooltip" src="/img/icon/info-icon_gray.svg">
                    </label>
                    <span class="inner-tooltip">The Milestone title should clearly identify the related stage of the project</span>
                </v-tooltip>
                <input placeholder="Enter Contract Job Title Here"
                       class="contract-draft__input"
                       type="text"
                       v-model="milestone.milestone_content.title"
                       disabled="disabled"
                >
            </div>
            <div class="contract-draft__input-box">
                <v-tooltip right>
                    <label slot="activator"
                           class="contract-draft__input-label">
                        Milestone Description
                        <img class="contract-draft__tooltip" src="/img/icon/info-icon_gray.svg">
                    </label>
                    <span>
                        The milestones description should provide clear<br>
                        sequence of events to be delivered for this particular <br>
                        phase of the project, as per the signed contract.
                    </span>
                </v-tooltip>
                <textarea class="contract-draft__textarea"
                          placeholder="Enter Brief Job Description"
                          ref="textareaDesc"
                          v-model="milestone.milestone_content.description"
                          disabled="disabled">
                        </textarea>
            </div>
            <template v-if="!read_only_milestone">
                <v-tooltip right>
                    <label class="contract-draft__input-label"
                           slot="activator">
                        File Attachments
                        <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'">
                    </label>
                    <span>
                        Upload one or more files to share important<br>
                        milestone related documents and images.
                    </span>
                </v-tooltip>
            </template>

            <file-attachments :readOnly="read_only_milestone" :batches="milestone.milestone_content.batches"/>

            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box">
                        <v-tooltip right>
                            <label class="contract-draft__input-label"
                                   slot="activator">
                                Approx. Start Date
                                <img class="contract-draft__tooltip" src="/img/icon/info-icon_gray.svg">
                            </label>
                            <span>The expected starting date for this specific Milestone.</span>
                        </v-tooltip>
                        <div class="contract-draft__input_dropdown">
                            <input class="contract-draft__input"
                                   placeholder="YY-MM-DD"
                                   :ref="'start_date_milestone'+ this.index"
                                   disabled="disabled"
                                   v-model="milestone.milestone_content.start_date"
                                   type="text"
                            >
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box">
                        <v-tooltip right>
                            <label class="contract-draft__input-label"
                                   slot="activator">
                                Approx. end date
                                <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'">
                            </label>
                            <span>The estimated end date for this specific Milestone.</span>
                        </v-tooltip>
                        <div class="contract-draft__input_dropdown">
                            <input placeholder="YY-MM-DD"
                                   class="contract-draft__input"
                                   disabled="disabled"
                                   v-model="milestone.milestone_content.end_date"
                                   type="text"
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box">
                        <v-tooltip right>
                            <label slot="activator"
                                   class="contract-draft__input-label"
                            >
                                Milestone Price, CAD
                                <img class="contract-draft__tooltip" src="/img/icon/info-icon_gray.svg">
                            </label>
                            <span>
                                Milestone prices distribute the total project cost over multiple billings.
                                When in progress, Milestone Prices may increase based on agreed upon changes during the milestone;
                                such changes must be identified in the Milestone details.
                                Once a milestone is successfully completed,Pros will submit a Milestone Payment Request via eRenovate.
                                Once payment is made and confirmed,the current milestone is marked as Completed and the next
                                milestone begins.
                            </span>
                        </v-tooltip>
                        <input placeholder="Enter Contract Price Here"
                               class="contract-draft__input"
                               v-money="money"
                               :disabled="read_only_milestone"
                               v-model.lazy="milestone.milestone_content.price">
                    </div>
                </div>
            </div>

            <contract-materials :milestone_index="index"
                                :readOnly="read_only_milestone"
                                :milestone_content="milestone.milestone_content"
            />

        </div>
    </div>
</template>
<script>
    import ContractMaterials from "../Material/Materials.vue"
    import FileAttachments from "../../../common/FileAttachments"
    import {resizeTextareaReadOnly, nextTickResize} from '../../../common/Mixins/textarea'
    import {minDate} from "../../../common/Mixins/datepicker";
    import {money} from "../../../common/Mixins/money";

    export default {
        mixins: [resizeTextareaReadOnly, nextTickResize, minDate, money],
        components: {
            'file-attachments': FileAttachments,
            'contract-materials': ContractMaterials
        },
        props: {
            open: {
                type: Boolean,
                required: true,
                default: false
            },
            index: Number,
            milestone: Object,
            readOnly: Boolean
        },
        data() {
            return {
                openstate: this.open,
                endDate: '',
                endDatemenu: false,
                startDate: '',
                startDatemenu: false,
                indexEl: this.id,
            }
        },
        computed: {
            endDateValidate(){
                return 'required|earlier_date:start_date_milestone' + this.index
            },
            milestone_title(){
                let title = this.milestone.milestone_content.title;
                if (title.length >= 40){
                    return title.substring(0, 40) + '...'
                }
                return title;
            },
            classFull(){
                if(this.$route.name !== 'contract-signed'){
                    return 'contract-draft__gray-box_full'
                }
                return false;
            },
            read_only_milestone(){
                if (this.$route.name === 'edited-milestone' && (this.milestone.status === 'completed' || this.milestone.invoice_count)) {
                    return true;
                }
                return this.readOnly
            }
        },
        mounted(){
            if (this.$route.name === 'edited-milestone' && this.milestone.status === 'completed') {
                $(this.$refs.milestone_box).slideToggle();
                this.openstate = false
            }
        },
        watch: {
            'milestone.milestone_content.start_date': function () {
                this.$store.commit('updateDraftSummary', 'start_date')
            },
            'milestone.milestone_content.end_date': function () {
                this.$store.commit('updateDraftSummary', 'end_date')
            },
            'milestone.milestone_content.price': function () {
                this.$store.commit('updateDraftSummary', 'service_cost');
                // this.$store.commit('updateDraftSummary', 'total_project_price');
            }
        },
        methods: {
            toggleOpen() {
                this.openstate = !this.openstate
                $(this.$refs.milestone_box).slideToggle();
            },
        },
        created(){
        },
        beforeUpdate(){

            if(this.readOnly){
                this.textareaDisabledResize(this.milestone.milestone_content.description)
            }
        },
    }
</script>
