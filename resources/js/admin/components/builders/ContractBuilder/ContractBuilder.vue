<template>

    <div :style="{height: windowHeight}"
         :class="{'contract-comp__body_signed': body_signed}"
         class="contract-comp__body component-body_scroll scrollbar">
        <div class="contract-draft__el-box" v-if="show_user_info">
            <div class="contract-draft__title-box" @click="toggleStageArg">
                <div class="contract-draft__title">Parties to the agreement</div>
                <img :src="'/img/icon/caret-icon_gray.svg'" :class="{active: argumentsToggle}" alt=""
                     class="contract-draft__curret">
            </div>

            <div ref="arguments_box" class="contract-draft__slide-main-box">
                <stage-agreements :current="contract.homeowner_info"
                                  :interlocutor="contract.contractor_info"
                                  :readOnly="readOnly"
                                  :status_signed="contract.status === 'signed'">
                </stage-agreements>
            </div>
        </div>

        <div class="contract-draft__el-box subject-matter__scroll-to">
            <div class="contract-draft__title-box" @click="changeSubjectMatter">
                <div class="contract-draft__title">Subject Matter</div>
                <img :src="'/img/icon/caret-icon_gray.svg'" :class="{active: subjectMatter}" alt=""
                     class="contract-draft__curret">
            </div>

            <div data-v-step="ccd-17" class="contract-draft__slide-main-box contract-draft__gray-box" :class="classFull"
                 ref="subjectMatter_box"
            >
                <subject-matter :contract_draft="contract_draft"
                                :guarantee_project="guarantee_project"
                                :readOnly="readOnlySubjectMatter">
                </subject-matter>

                <template v-if="contract_draft.type === 'single' && (!readOnly || hasMaterials)">
                    <contract-materials :milestone_index="0"
                                        :milestone_content="firstMilestone().milestone_content"
                                        :readOnly="readOnlySubjectMatter">
                    </contract-materials>
                </template>

            </div>
        </div>

        <template v-if="contract_draft.type === 'several'">
            <milestones :milestones="contract_draft.milestones"
                        :readOnly="readOnly">
            </milestones>
        </template>

        <payment-list :summary_id="contract_draft.summary.id"
                      :payments="summary.payments"
                      :payment_total="summary.payment_total"
                      :contract_status="contract.status"/>

    </div>

</template>
<script>
    import StageAgreement from "./StageAgreement/StageAgreement"
    import SubjectMatter from "./SubjectMatter/SubjectMatter"
    import Milestones from "./Milestone/Milestones"
    import ContractMaterials from "./Material/Materials.vue"

    import {ContainerHeight} from '../../common/Mixins/builder'
    import {mapGetters} from 'vuex'
    import Payments from './Payments/PaymentList'

    export default {
        mixins:[ContainerHeight],
        components: {
            'stage-agreements': StageAgreement,
            'subject-matter': SubjectMatter,
            'milestones': Milestones,
            'contract-materials': ContractMaterials,
            'payment-list':Payments
        },
        data() {
            return {
                argumentsToggle: true,
                subjectMatter: true,
            }
        },
        props: {
            contentHeight: [Boolean, Number],
            readOnly: Boolean,
            show_user_info: {
                type: Boolean,
                default: true
            },
            contract_draft: Object,
            contract: Object,
            guarantee_project: Object,
            body_signed: {
                type: Boolean,
                default: false
            },
        },
        computed: {
            classFull(){
                if(this.$route.name !== 'contract-signed'){
                    return 'contract-draft__gray-box_full'
                }
                return false;
            },
            ...mapGetters(["user", "summary"]),

            hasMaterials(){
                return this.firstMilestone().milestone_content.materials.length || this.firstMilestone().milestone_content.material_files.length;
            },

            readOnlySubjectMatter(){
                if (this.$route.name === 'edited-milestone') {

                    let has_edited = false;
                    this.contract_draft.milestones.forEach((milestone) => {
                        if ((milestone.milestone_content.status === 'pending' || milestone.milestone_content.status === 'completed') && this.contract_draft.type === 'single') {
                            has_edited = true
                        }
                    });

                    return has_edited || this.contract_draft.type === 'several';
                }

                return this.readOnly;
            }
        },
        methods: {
            firstMilestone(){
                return this.contract_draft.milestones[0];
            },
            changeSubjectMatter() {
                this.subjectMatter = !this.subjectMatter;
                $(this.$refs.subjectMatter_box).slideToggle()
            },
            toggleStageArg() {
                this.argumentsToggle = !this.argumentsToggle;
                $(this.$refs.arguments_box).slideToggle();
            },
        },
        mounted(){
            if (this.$route.name === 'edited-milestone') {
                $(this.$refs.arguments_box).slideToggle();
                this.argumentsToggle = true;

                if (this.contract_draft.type === 'several') {
                    $(this.$refs.subjectMatter_box).slideToggle();
                    this.subjectMatter = true;
                }
            }
        },
    }
</script>
