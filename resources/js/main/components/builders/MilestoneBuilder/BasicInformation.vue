<template>
    <div class="contract-draft__el-box">
        <div class="contract-draft__title-box" @click="toggleBlock">
            <div class="contract-draft__title">Milestone Details</div>
            <img class="contract-draft__curret" :class="{ active: iconToggle }" :src="'/img/icon/caret-icon_gray.svg'">
        </div>

        <div class="contract-draft__slide-main-box contract-draft__gray-box_invoice contract-draft__gray-box"
             ref="arguments_box">

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label"
                       :class="{'invalid-input':  errors.has('title')}"
                       data-v-step="mls-3">
                    Milestone Title
                </label>
                <input placeholder="Enter Invoice Title"
                       class="contract-draft__input"
                       type="text"
                       data-vv-as="milestone title"
                       :class="inputsClassList('title')"
                       v-model="milestone_content.title"
                       @focus="clearErrors('title')"
                       :disabled="read_only_milestone"
                       v-validate="'required'"
                       name="title"
                       key="title"
                >
                <div v-if="errors.has('title')" class="invalid-message">
                    {{ errors.first('title') }}
                </div>
                <div v-else-if="isPreviousChanged('title')" class="old-version-message">
                    Previous value : {{ edited_milestone.milestone_content.title }}
                </div>
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('description')}">
                    Milestone Description
                </label>
                <textarea placeholder="Enter Brief Job Description"
                          class="contract-draft__textarea"
                          ref="textareaDesc"
                          data-vv-as="milestone description"
                          :class="inputsClassList('description')"
                          v-model="milestone_content.description"
                          @focus="clearErrors('description')"
                          :disabled="read_only_milestone"
                          v-validate="'required'"
                          name="description"
                          key="description"
                ></textarea>
                <div v-if="errors.has('description')" class="invalid-message">
                    {{ errors.first('description') }}
                </div>
                <div v-else-if="isPreviousChanged('description')" class="old-version-message">
                    Previous value : {{ edited_milestone.milestone_content.description }}
                </div>
            </div>

            <div class="row">

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label"
                               :class="{'invalid-input':  errors.has('start_date')}">
                            Approx. Start Date
                        </label>

                        <input-date v-model="milestone_content.start_date"
                                    :error="errors.first('start_date')"
                                    @focus="clearErrors('start_date')"
                                    :readOnly="read_only_milestone"
                                    v-validate="'required'"
                                    data-vv-as="start_date"
                                    label="start_date"
                                    :isValidate="true"
                                    ref="start_date"

                                    :is_previous_changed="isPreviousChanged('start_date')"
                                    :previous_value="edited_milestone ? edited_milestone.milestone_content.start_date : ''"
                        ></input-date>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('end_date')}">
                            Approx. End Date
                        </label>
                        <input-date v-model="milestone_content.end_date"
                                    :error="errors.first('end_date')"
                                    @focus="clearErrors('end_date')"
                                    :readOnly="read_only_milestone"
                                    v-validate="'required'"
                                    data-vv-as="end_date"
                                    label="end_date"
                                    :isValidate="true"
                                    ref="end_date"

                                    :is_previous_changed="isPreviousChanged('end_date')"
                                    :previous_value="edited_milestone ? edited_milestone.milestone_content.end_date : ''"
                        ></input-date>
                    </div>
                </div>

                <template v-if="milestone.status === 'completed'">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="contract-draft__input-box">
                            <label slot="activator" class="contract-draft__input-label">Completed</label>
                            <div class="contract-draft__input_dropdown">
                                <input placeholder="MM-DD-YY"
                                       disabled="disabled"
                                       v-model="milestone_content.completed_at"
                                       type="text"
                                       class="contract-draft__input">
                            </div>
                        </div>
                    </div>
                </template>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('price')}">
                            Total Milestone Price
                        </label>
                        <input placeholder="Milestone price"
                               class="contract-draft__input"
                               type="text"
                               data-vv-as="milestone price"
                               :class="inputsClassList('price')"
                               @focus="clearErrors('price')"
                               :disabled="read_only_milestone"
                               name="price"
                               key="price"
                               v-model.lazy="milestone_content.price"
                               v-validate="'required|formatted_price'"
                               v-money="money"
                               pattern="\d+"
                               min="0.01"
                        >
                        <div v-if="errors.has('price')" class="invalid-message">
                            {{ errors.first('price') }}
                        </div>
                        <div v-else-if="isPreviousChanged('price')" class="old-version-message">
                            Previous value : {{ edited_milestone.milestone_content.price }}
                        </div>
                    </div>
                </div>
            </div>

            <template v-if="milestone_content.batches.length || !read_only_milestone">
                <div class="contract-draft__input-box">
                    <label class="contract-draft__input-label">Milestone Related Attachments</label>
                    <file-attachments :batches="milestone_content.batches"
                                      :has_changed_files="has_changed_files"
                                      :batches_old_version="old_version_batches"
                                      :readOnly="read_only_milestone">
                    </file-attachments>
                </div>
            </template>

        </div>
    </div>
</template>

<script>
    import FileAttachments from '../../common/FileAttachments.vue'
    import DatePicker from '../../common/elements/DatePicker'
    import {nextTickResize, resizeTextareaReadOnly} from '../../common/Mixins/textarea';
    import {mapGetters} from 'vuex'
    import {ChildMixin} from "../../common/Mixins/glValidate/mixins";
    import {money} from "../../common/Mixins/money";

    export default {
        components: {
            'input-date': DatePicker,
            'file-attachments': FileAttachments,
        },
        mixins: [nextTickResize, resizeTextareaReadOnly, ChildMixin, money],
        data() {
            return {
                iconToggle: true,
                previous_value: 'hello old',
                isPreviusValue: false
            }
        },
        props: {
            milestone: Object,
            edited_milestone: Object,
            read_only: Boolean,
        },
        computed: {
            ...mapGetters(["read_only_milestone"]),
            milestone_content() {
                return this.milestone.milestone_content
            },
            old_version_batches(){
                return this.edited_milestone ? this.edited_milestone.milestone_content.batches : false;
            },
            has_changed_files() {
                let current_butches = this.milestone.milestone_content.batches;
                let edited_milestone_batches = this.edited_milestone ? this.edited_milestone.milestone_content.batches : undefined;

                if (edited_milestone_batches !== undefined) {
                    return edited_milestone_batches.length !== current_butches.length ||
                        edited_milestone_batches.some((batch, index) => {
                            return batch.length > current_butches[index].length || batch.message !== current_butches.message;
                        })
                }

                return false;
            }
        },
        methods: {
            toggleBlock() {
                this.iconToggle = !this.iconToggle;
                $(this.$refs.arguments_box).slideToggle();
            },
            inputsClassList(name) {
                if (this.errors.has(name)) {
                    return 'invalid-input scroll__invalid-input'
                } else if (this.isPreviousChanged(name)) {
                    return 'changed-input'
                }
            },
            isPreviousChanged(name) {
                return this.edited_milestone && this.edited_milestone.milestone_content[name] !== this.milestone.milestone_content[name]
            }
        },
        beforeUpdate() {
            if (this.read_only_milestone) {
                this.textareaDisabledResize(this.milestone_content.description)
            }
        },
    }
</script>
