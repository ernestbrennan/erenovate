<template>
    <div class="summary-block">
        <h3 class="summary-block__border-title">Confirm Project Completion</h3>
        <div class="summary-block__gray-box">
            <p class="summary-block__complete-text">
                To complete the Project and activate your 88 Day Workmanship Guarantee, we kindly ask you to review
                your experience working with the Bonded Pro, plus confirm the final Project Price. If the final project price
                you paid is different than shown above click the Report a Price
                Variance link below <b>after writing your review</b>.
            </p>
            <p class="summary-block__complete-text">
                <b>Note:</b> Confirming final Project Price is required to activate the 88 Day Workmanship Guarantee for your
                project. Failure to report a Price Discrepancy (ex. If final Project Price is higher than shown above) may
                result in a loss of compensation or coverage by the 88 Day Guarantee.

            </p>
            <label class="contract-draft__input-label sum-tour-12s">Leave a review</label>

            <textarea
                    ref="textareaDesc"
                    placeholder="Please write a review of your experience working with this Business"
                    class="contract-draft__textarea summary-block__textarea"
                    name="contract description"
                    @input="checkText"
                    @focus="clearError"
                    key="contract_description"
                    v-model="description">
            </textarea>

            <div class="comp-cont-row">
                <div class="left-side">
                    <label class="sum-tour-13s contact-draft__checkbox" for="representative-checkbox-id">
                        <input type="checkbox"
                               id="representative-checkbox-id"
                               name="company_checkbox"
                               v-model="checkbox"
                               class="d-none">
                        <span ref="checkbox" class=""></span>
                    </label>
                    <label class="contract-draft__input-label">I confirm Total Project Price shown above is correct</label>
                </div>
                <div class="right-side sum-tour-14s">
                    <button class=" claim-price" @click="priceModification">
                        REPORT A PRICE VARIANCE
                    </button>
                </div>
            </div>
            <div style="text-align: center;">
                <button :disabled="loading" class="main-btn main-btn_full-green complete-button" @click="completeContract">
                    <template v-if="loading === true">
                        <v-progress-circular
                            indeterminate
                            color="white"
                        ></v-progress-circular>
                    </template>
                    <template v-else>
                        Close project and start 88 day guarantee
                    </template>
                </button>
                <!--<p class="common-p">and request my 88 day workmanship guarantee</p>-->
            </div>
        </div>
        <issue-dialog v-model="dialogIssue"></issue-dialog>
    </div>
</template>

<script>
    import {nextTickResize} from "../../../common/Mixins/textarea";
    import {mapGetters} from 'vuex'
    import issueDialog from './IssueDialog'

    export default {
        components: {
            'issue-dialog': issueDialog,
        },
        mixins: [nextTickResize],
        data() {
            return {
                description: '',
                dialogIssue: false,
                checkbox: false,
                loading: false,
            }
        },
        computed: {
            ...mapGetters(["user", 'summary'])
        },
        methods: {
            clearError() {
                $(this.$refs.textareaDesc).removeClass('input_invalid');
            },
            checkText() {
                if (!this.description.length) {
                    return $(this.$refs.textareaDesc).addClass('input_invalid')
                } else {
                    return $(this.$refs.textareaDesc).removeClass('input_invalid')
                }
            },
            completeContract() {
                if (!this.description.length) {
                    return $(this.$refs.textareaDesc).addClass('input_invalid')
                }
                if (!this.checkbox) {
                    return $(this.$refs.checkbox).addClass('invalid-input')
                }
                this.loading = true
                api.post('contract.confirmComplete', {
                    guarantee_project_id: this.$route.params.guarantee_project_id,
                    comment: this.description,
                })
                    .then(response => {
                        this.loading = false
                        this.$router.push({
                            name: 'messages',
                            params: {guarantee_project_id: this.$route.params.guarantee_project_id}
                        })
                    });
            },
            priceModification() {
                if (this.summary.price_modification) {
                    return this.$router.push({
                        name: 'issue',
                        params: {
                            guarantee_project_id: this.$route.params.guarantee_project_id,
                            issue_id: this.summary.price_modification.id,
                        }
                    })
                }

                this.dialogIssue = true
            }
        },
    }
</script>

<style>
    .input_invalid {
        border: 2px solid #F13A30 !important;
    }
</style>
