<template>
    <div class="contract-draft__el-box" :class="'milestone_' + (index + 1)">
        <div class="contract-draft__title-box">
            <div class="contract-draft__title" @click="toggleOpen">
                <span :class="{'completed': milestone.status === 'completed'}" class="milestone-title-builder">{{ milestone_title }}</span>
                <span class=""> - Milestone {{ index + 1 }}</span>
                <span class="milestone-complete-label" v-if="milestone.status === 'completed'">Completed</span>
            </div>
            <div class="contract-draft__el-controls">

                <img class="contract-draft__delete-mileston"
                     v-if="!readOnly"
                     :src="'/img/icon/stop_gray.svg'"
                     v-on:click="$emit('removeMilestone', index)">

                <img :src="'/img/icon/order_gray.svg'"
                     class="contract-draft__order-mileston"
                     v-if="!readOnly">

                <img :src="'/img/icon/caret-icon_gray.svg'"
                     class="contract-draft__curret"
                     :class="{active: openstate}"
                     @click="toggleOpen">
            </div>
        </div>

        <div class="contract-draft__slide-main-box contract-draft__gray-box" :class="class_full" ref="milestone_box">

            <div class="contract-draft__input-box">
                <v-tooltip right>
                    <label class="contract-draft__input-label"
                           :class="{'invalid-input':  errors.has( validationProp('milestone_title') ) }"
                           slot="activator">
                        Milestone Title
                        <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'">
                    </label>
                    <span class="inner-tooltip">The Milestone title should clearly identify the related stage of the project</span>
                </v-tooltip>
                <input placeholder="Milestone title"
                       class="contract-draft__input"
                       type="text"
                       v-model="milestone.milestone_content.title"
                       :disabled="readOnly"
                       :class="{'invalid-input':  errors.has(validationProp('milestone_title')), 'scroll__invalid-input':  errors.has(validationProp('milestone_title'))}"
                       v-validate="'required'"
                       @focus="clearErrors(validationProp('milestone_title'))"
                       :data-vv-name="validationProp('milestone_title')"
                       :name="validationProp('milestone_title')"
                       :key="validationProp('milestone_title')"
                >
                <div v-if="errors.has(validationProp('milestone_title'))" class="invalid-message">
                    The milestone title field is required.
                </div>
            </div>

            <div class="contract-draft__input-box">
                <v-tooltip right>
                    <label slot="activator"
                           :class="{'invalid-input':  errors.has(validationProp('milestone_description')) }"
                           class="contract-draft__input-label">
                        Milestone Description
                        <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'">
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
                          @keyup="resizeTextarea"
                          @keydown="resizeTextarea"
                          v-model="milestone.milestone_content.description"
                          :disabled="readOnly"
                          :class="{'invalid-input':  errors.has(validationProp('milestone_description')) , 'scroll__invalid-input':  errors.has(validationProp('milestone_description'))}"
                          v-validate="'required'"
                          @focus="clearErrors(validationProp('milestone_description'))"
                          :data-vv-name="validationProp('milestone_description')"
                          :name="validationProp('milestone_description')"
                          :key="validationProp('milestone_description')"
                >
                        </textarea>
                <div v-if="errors.has(validationProp('milestone_description'))" class="invalid-message">
                    The milestone description field is required.
                </div>
            </div>

            <template v-if="!readOnly">
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

            <file-attachments :id_dropzone="'milestone_'+( index + 1)"
                              :readOnly="readOnly"
                              :batches="milestone.milestone_content.batches"/>

            <div class="row">

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box">
                        <v-tooltip right>
                            <label class="contract-draft__input-label"
                                   :class="{'invalid-input':  errors.has(validationProp('start_date')) }"
                                   slot="activator">
                                Approx. Start Date
                                <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'">
                            </label>
                            <span>The expected starting date for this specific Milestone.</span>
                        </v-tooltip>
                        <div class="contract-draft__input_dropdown">
                            <input class="contract-draft__input"
                                   placeholder="YY-MM-DD"
                                   :ref="'start_date_milestone'+ this.index"
                                   :class="{'invalid-input':  errors.has(validationProp('start_date')), 'scroll__invalid-input':  errors.has(validationProp('start_date')) }"
                                   :disabled="datePickerDisabled('startDatemenu')"
                                   @focus="toggleVal('startDatemenu','start_date')"
                                   v-model="milestone.milestone_content.start_date"
                                   data-vv-as="start date"
                                   :name="validationProp('start_date')"
                                   :key="validationProp('start_date')"
                                   :data-vv-name="validationProp('start_date')"
                                   v-validate="'required'"
                                   type="text"
                            >
                            <div v-if="errors.has(validationProp('start_date'))" class="invalid-message">
                                {{ errors.first(validationProp('start_date')) }}
                            </div>
                            <v-date-picker v-if="startDatemenu"
                                           @dblclick.native="changeTime('startDatemenu','start_date')"
                                           :min="minDate"
                                           v-model="milestone.milestone_content.start_date"
                                           no-title
                                           scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="startDatemenu = false">Cancel</v-btn>
                                <v-btn flat color="primary" @click="changeTime('startDatemenu','start_date')">OK</v-btn>
                            </v-date-picker>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box">
                        <v-tooltip right>
                            <label class="contract-draft__input-label"
                                   :class="{'invalid-input':  errors.has(validationProp('end_date')) }"
                                   slot="activator">
                                Approx. end date
                                <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'">
                            </label>
                            <span>The estimated end date for this specific Milestone.</span>
                        </v-tooltip>
                        <div class="contract-draft__input_dropdown">
                            <input placeholder="YY-MM-DD"
                                   class="contract-draft__input"
                                   :class="{'invalid-input':  errors.has(validationProp('end_date')), 'scroll__invalid-input':  errors.has(validationProp('end_date')) }"
                                   :disabled="datePickerDisabled('endDatemenu')"
                                   v-model="milestone.milestone_content.end_date"
                                   @focus="toggleVal('endDatemenu','end_date')"
                                   data-vv-as="end date"
                                   :name="validationProp('end_date')"
                                   :key="validationProp('end_date')"
                                   :data-vv-name="validationProp('end_date')"
                                   v-validate="end_date_validate"
                                   type="text">
                            <div v-if="errors.has(validationProp('end_date'))" class="invalid-message">
                                {{ errors.first(validationProp('end_date')) }}
                            </div>
                            <v-date-picker v-if="endDatemenu"
                                           @dblclick.native="changeTime('endDatemenu','end_date')"
                                           :min="minDateEnd(milestone.milestone_content.start_date)"
                                           v-model="milestone.milestone_content.end_date"
                                           no-title
                                           scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="endDatemenu = false">Cancel</v-btn>
                                <v-btn flat color="primary" @click="changeTime('endDatemenu','end_date')">OK</v-btn>
                            </v-date-picker>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box">
                        <v-tooltip right>
                            <label class="contract-draft__input-label"
                                   :class="{'invalid-input':  errors.has(validationProp('milestone_price')) }"
                                   slot="activator">
                                Milestone Price, CAD
                                <img class="contract-draft__tooltip" :src="'/img/icon/info-icon_gray.svg'">
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
                               @focus="clearErrors(validationProp('milestone_price'))"
                               :disabled="readOnly"
                               v-model.lazy="milestone.milestone_content.price"
                               v-validate="'required|formatted_price'"
                               :name="validationProp('milestone_price')"
                               :key="validationProp('milestone_price')"
                               :class="{'invalid-input':  errors.has(validationProp('milestone_price')) , 'scroll__invalid-input':   errors.has(validationProp('milestone_price'))}"
                        >
                        <div v-if="errors.has(validationProp('milestone_price'))" class="invalid-message">
                            {{ errors.first(validationProp('milestone_price')) }}
                        </div>
                    </div>
                </div>
            </div>

            <contract-materials :milestone_index="index" :readOnly="readOnly" :milestone_content="milestone.milestone_content"/>

        </div>
    </div>
</template>
<script>
    import ContractMaterials from "../Material/Materials.vue"
    import FileAttachments from "../../../common/FileAttachments"
    import child_validator from "../../../mixins/validator/base/child_validator";
    import {resizeTextareaReadOnly, nextTickResize} from '../../../common/Mixins/textarea'
    import {minDate} from "../../../common/Mixins/datepicker";
    import {money} from "../../../common/Mixins/money";

    export default {
        mixins: [child_validator, resizeTextareaReadOnly, nextTickResize, minDate, money],
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
                index_number: this.index + 1,
            }
        },
        computed: {
            end_date_validate() {
                return 'required|earlier_date:start_date_milestone' + this.index
            },
            milestone_title() {
                let title = this.milestone.milestone_content.title;
                if (title && title.length >= 40) {
                    return title.substring(0, 40) + '...'
                }
                return title;
            },
            class_full() {
                return this.$route.name !== 'contract-signed' ? 'contract-draft__gray-box_full' : false
            },

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
            }
        },
        methods: {
            createValidateEndDate() {
                let self = this;
                this.$validator.extend('earlier_milestone', {
                    getMessage(field, val) {
                        return 'Start date bigger then end date'
                    },
                    validate(value, field) {

                        if (!value.length || !self.milestone.milestone_content.start_date) {
                            return false
                        }
                        let startParts = self.milestone.milestone_content.start_date.split('-');

                        let endParts = value.split('-');
                        let start = new Date(startParts[2], startParts[1] - 1, startParts[0]) // month is 0-based
                        let end = new Date(endParts[2], endParts[1] - 1, endParts[0])

                        return end > start
                    }
                })
            },
            validationProp(prop) {
                return prop + this.index
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
            toggleVal(picker, model) {
                this.clearErrors(this.validationProp(model));
                this.milestone.milestone_content[model] = this.parseDate(this.milestone.milestone_content[model]);
                if (picker === 'endDatemenu' && this.startDatemenu === false) {
                    this.endDatemenu = true
                } else if (picker === 'startDatemenu' && this.endDatemenu === false) {
                    this.startDatemenu = true
                }

            },
            changeTime(picker, val) {
                this.milestone.milestone_content[val] = this.formatDate(this.milestone.milestone_content[val]);
                this[picker] = !this[picker]
            },
            formatDate(date) {
                if (!date) return null;
                return date
            },
            parseDate(date) {
                if (!date) return null;
                return date
            },
            toggleOpen() {
                this.openstate = !this.openstate;
                $(this.$refs.milestone_box).slideToggle();
            },
        },
        created() {
            this.createValidateEndDate();
        },
        beforeUpdate() {
            if (this.readOnly) {
                this.textareaDisabledResize(this.milestone.milestone_content.description)
            }
        },
    }
</script>
