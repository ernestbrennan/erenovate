<template>
    <div>
        <div class="contract-draft__input-box">
            <v-tooltip right>
                <label slot="activator" class="contract-draft__input-label">
                    Project Title
                    <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'" alt="">
                </label>
                <span>Name of Project identifying the project you submitted using the Post My Project tool.</span>
            </v-tooltip>

            <input placeholder="Enter Project Job Title Here"
                   type="text"
                   name="project title"
                   class="contract-draft__input"
                   v-model="title"
                   :disabled="readOnly"
                   key="contract_title"
            >
        </div>
        <div class="contract-draft__input-box">
            <v-tooltip right>
                <label data-v-step="ccd-7" slot="activator" class="contract-draft__input-label">
                    Project Description
                    <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'" alt="">
                </label>
                <span>
                    Provide an overview of the project such as the overall objectives. <br>
                    Be succinct, and to the point.
                </span>
            </v-tooltip>
            <textarea ref="textareaDesc"
                      placeholder="Enter Brief Job Description"
                      class="contract-draft__textarea"
                      :disabled="readOnly"
                      v-model="description"
            >
            </textarea>
        </div>

        <template v-if="file">
            <div class="contract-draft__input-box">
                <v-tooltip right>
                    <label slot="activator" class="contract-draft__input-label">
                        Signed Contract
                        <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'" alt="">
                    </label>
                    <span>
                    Attaching Signed Contract between parties here is
                    required to send Project Scope to client, and is
                    required to ensure project is covered by the eRenovate
                    Guarantee.
                </span>
                </v-tooltip>

                <div class="contract-draft__contract-dl table-files">
                    <div class="table-files__name-size-row">
                        <div class="table-files__icon-name">
                            <div class="img-position"><img :src="'/img/icon/chat-file.svg'" alt=""></div>
                            <span>{{ file.name }}</span>
                        </div>
                        <div class="table-files__sizes">
                            <span v-html="formatSize(file.size)"></span>
                        </div>
                    </div>
                    <div class="table-files__link">
                        <a @click.prevent="downloadWithLoading(file.id)">
                            <template v-if="is_mobile">
                                <template v-if="loading">
                                    <v-progress-circular indeterminate color="primary"></v-progress-circular>
                                </template>
                                <template v-else>
                                    <img src="/img/icon/download-mobile-chat_blue.svg" alt="">
                                </template>
                            </template>
                            <template v-else>
                                <span class="table-files__download">DOWNLOAD</span>
                            </template>
                        </a>
                    </div>
                </div>
            </div>
        </template>

        <!--contract-attachment data-v-step="ccd-8" :readOnly="readOnly" :contract_draft="contract_draft"/-->

        <template v-if="!readOnly && $route.name !== 'edited-milestone'">
            <v-tooltip right>
                <label slot="activator" class="contract-draft__input-label">Project Milestones
                    <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'" alt="">
                </label>
                <span>
                    The Project milestones identify the key points or <br>
                    stages along the course of the project, to track <br>
                    progress and ensure the key deliverables are being <br>
                    achieved for each stage. A simple project can have a <br>
                    Single Milestone versus Several Milestones for a <br>
                    larger project as agreed to by both parties.
                </span>
            </v-tooltip>
            <div class="toggle-buttons toggle-buttons_mobile-column" data-v-step="ccd-11">
                <button class="toggle-buttons__el left"
                        data-v-step="ccd-12"
                        :class="{active: contract_draft.type === 'single'}"
                        @click="changeType('single')">
                    SINGLE MILESTONE
                </button>
                <button class="toggle-buttons__el right"
                        :class="{active: contract_draft.type === 'several'}"
                        @click="changeType('several')">
                    SEVERAL MILESTONES
                </button>
            </div>
        </template>
        <template v-if="contract_draft.type === 'single'">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box">
                        <v-tooltip right>
                            <label slot="activator"
                                    class="contract-draft__input-label">Approx. start date
                                <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'" alt="">
                            </label>
                            <span>The expected starting date for this specific Milestone.</span>
                        </v-tooltip>
                        <input-date
                                ref="start_date"
                                v-model="contract_draft.milestones[0].milestone_content.start_date"
                                :isValidate="false"
                                label="start date"
                                :readOnly="datePickerDisabled('startDatemenu')"
                                key="end_date_subMetter"
                        >
                        </input-date>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box">
                        <v-tooltip right>
                            <label slot="activator" class="contract-draft__input-label"
                                   :class="{'invalid-input':  errors.has('end date'),'scroll__invalid-input':  errors.has('end date') }"
                            >
                                Approx. end date
                                <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'" alt="">
                            </label>
                            <span>The estimated end date for this specific Milestone.</span>
                        </v-tooltip>
                        <input-date
                                ref="end_date"
                                v-model="contract_draft.milestones[0].milestone_content.end_date"
                                :isValidate="false"
                                label="end date"
                                :setMinDate="contract_draft.milestones[0].milestone_content.start_date"
                                :readOnly="datePickerDisabled('endDatemenu')"
                        >
                        </input-date>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box">
                        <v-tooltip right>
                            <label slot="activator"
                                   class="contract-draft__input-label">{{ titleContractPrice }}
                                <img class="contract-draft__tooltip" src="/img/icon/info-icon_gray.svg" alt="">
                            </label>
                            <span>
                                The total cost specific to complete the <b>project </b>or<br>
                                work outlined in the Project Contract. Any project <br>
                                changes during the project can increase the total <br>
                                project cost at project completion.
                            </span>
                        </v-tooltip>
                        <input placeholder="Enter Contract Price Here"
                               class="contract-draft__input"
                               min="0.01"
                               pattern="\d+"
                               name="first_milestone_price"
                               :disabled="readOnly"
                               v-money="money"
                               v-model.lazy="price"
                        >
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>
<script>
    import FileAttachments from "../../../common/FileAttachments"
    import DatePicker from '../../../common/elements/DatePicker'
    import {nextTickResize, resizeTextareaReadOnly} from "../../../common/Mixins/textarea";
    import {minDate} from "../../../common/Mixins/datepicker";
    import {money} from "../../../common/Mixins/money";
    import dropzone from '../../../../../main/components/mixins/dropzone/dropzone.js';

    export default {
        mixins: [resizeTextareaReadOnly, nextTickResize, minDate, money, dropzone],
        components: {
            'file-attachments': FileAttachments,
            'input-date': DatePicker
        },
        data() {
            return {
                endDatemenu: false,
                startDatemenu: false,
            }
        },
        props: [
            'contract_draft',
            'guarantee_project',
            'readOnly'
        ],
        computed: {
            hasBatchesTitle() {
                if (this.readOnly && this.contract_draft.batches.length > 0) {
                    return true
                } else if (this.readOnly === false) {
                    return true
                } else {
                    return false
                }
            },
            titleContractPrice() {
                if (this.contract_draft.type === 'single') {
                    return 'Project Price, CAD'
                }
                return 'Project Basic Price, CAD'
            },
            title: {
                get: function () {
                    return this.getProp('title')
                },
                set: function (value) {
                    this.setProp('title', value)
                }
            },
            description: {
                get: function () {
                    return this.getProp('description')
                },
                set: function (value) {
                    this.setProp('description', value)
                }
            },
            price: {
                get: function () {
                    return this.contract_draft.milestones[0].milestone_content.price;
                },
                set: function (value) {
                    return this.contract_draft.milestones[0].milestone_content.price = value;
                }
            },
            file() {
                if (this.contract_draft.contract_signed && this.contract_draft.contract_signed.file) {
                    return this.contract_draft.contract_signed.file;
                } else {
                    return false;
                }
            }
        },
        watch: {
            date(val) {
                this.dateFormatted = this.date
            },
            'price': function () {
                this.$store.commit('updateDraftSummary', 'service_cost')
            },
            'contract_draft.type': function () {
                if (this.contract_draft.type === 'several') {
                    if (!this.contract_draft.title) {
                        this.contract_draft.title = this.contract_draft.milestones[0].milestone_content.title;
                    }
                    if (!this.contract_draft.description) {
                        this.contract_draft.description = this.contract_draft.milestones[0].milestone_content.description;
                    }
                }
            },
        },
        methods: {
            setDisabled(value,model){
                this[model] = value;
            },
            getProp(prop) {
                if (this.contract_draft.type === 'single') {
                    return this.contract_draft.milestones[0].milestone_content[prop];
                }
                return this.contract_draft[prop];
            },
            setProp(prop, value) {
                if (this.contract_draft.type === 'single') {
                    return this.contract_draft.milestones[0].milestone_content[prop] = value;
                }
                return this.contract_draft[prop] = value;
            },
            changeTime(picker, val) {
                this[picker] = !this[picker]
            },
            datePickerDisabled(value) {
                if (this.readOnly === true) {
                    return true
                } else if (this.startDatemenu && this.endDatemenu === false) {
                    return true
                } else if (this.endDatemenu && this.startDatemenu === false) {
                    return true
                }
                return this[value];
            },
            scrollToBlock(target_block) {
                const container = $('.contract-comp__body'),
                    scrollTo = $(target_block);
                if (typeof scrollTo !== 'undefined') {
                    container.animate({
                        scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop() - 100
                    }, 1000);
                }
            },
            changeType(type) {
                if (type === 'several') {
                    this.$store.dispatch('calculateSummary');
                    const timer = setTimeout(() => {
                        this.scrollToBlock('.contact-draft__milestones-list');
                        clearTimeout(timer)
                    }, 200);
                } else if ('single') {
                    this.scrollToBlock('.subject-matter__scroll-to');
                }
                this.contract_draft.type = type;
            },
        },
        created() {
        },
        beforeUpdate() {
            if (this.readOnly) {
                this.textareaDisabledResize(this.getProp('description'))
            }
        },
    }
</script>
<style>

</style>
