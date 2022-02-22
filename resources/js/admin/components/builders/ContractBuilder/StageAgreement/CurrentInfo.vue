<template>
    <div class="contract-draft__gray-box contract-draft__gray-box_min-height contract-draft__personal-data">

        <form @submit.prevent="validateUserInformation">
            <h3 class="contract-draft__box-title">HO Contact Information</h3>
            <!--<div class="toggle-buttons">-->
                <!--<button class="toggle-buttons__el left"-->
                        <!--:class="{active: type === 'individual'}"-->
                        <!--disabled="disabled"-->
                <!--&gt;-->
                    <!--INDIVIDUAL-->
                <!--</button>-->
                <!--<button class="toggle-buttons__el right"-->
                        <!--:class="{active: type === 'legal'}"-->
                        <!--disabled="disabled"-->
                <!--&gt;-->
                    <!--LEGAL ENTITY-->
                <!--</button>-->
            <!--</div>-->
            <template v-if="current.type === 'individual'">
                <div class="contract-draft__input-box">
                    <label class="contract-draft__input-label">
                        First Name
                    </label>
                    <input type="text"
                           class="contract-draft__input"
                           placeholder="Example: John"
                           v-model="current.first_name"
                           disabled="disabled"
                           name="first name"
                    >
                </div>
                <div class="contract-draft__input-box">
                    <label class="contract-draft__input-label">Last Name</label>
                    <input type="text"
                           name="last name"
                           class="contract-draft__input"
                           placeholder="Example: Jones"
                           v-model="current.last_name"
                           disabled="disabled">
                </div>
            </template>
            <template v-if="current.type === 'legal'">

                <div class="contract-draft__input-box">
                    <label class="contract-draft__input-label">Company Name</label>
                    <input type="text"
                           name="company name"
                           class="contract-draft__input"
                           placeholder="Example: Erenovate LLC"
                           v-model="current.company_name"
                           disabled="disabled"
                    >
                </div>
                <div class="contract-draft__input-box">
                    <label class="contract-draft__input-label">Name of Representative</label>
                    <input type="text"
                           name="representative"
                           class="contract-draft__input"
                           placeholder="Example: John Doe"
                           v-model="current.representative_name"
                           disabled="disabled"
                    >
                </div>
                <div class="contract-draft__input-box g-flex g-flex_row g-flex_wrap" v-if="!canCurrentEdit">
                    <label
                            class="contact-draft__checkbox"
                            for="representative-checkbox-id">
                        <input type="checkbox"
                               id="representative-checkbox-id"
                               v-model="company_checkbox"
                               class="d-none"
                               disabled="disabled"
                        >
                        <span></span>
                    </label>
                    <label class="contract-draft__checkbox-label" for="representative-checkbox-id">
                        I confirm that I am authorized to bind the corporation into this agreement
                    </label>
                </div>
            </template>

            <div class="contract-draft__sep-title">Address</div>
            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label">Address Line 1</label>
                <input type="text"
                       name="address 1"
                       class="contract-draft__input"
                       placeholder="Example: 1 Main St. SE"
                       v-model="current.address_1"
                       disabled="disabled">
            </div>
            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label">Address Line 2</label>
                <input type="text"
                       name="address 2"
                       class="contract-draft__input"
                       placeholder="Example: App. 123"
                       v-model="current.address_2"
                       disabled="disabled">
            </div>
            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label">City</label>
                <input type="text"
                       class="contract-draft__input"
                       placeholder="Example: Toronto"
                       v-model="current.city"
                       disabled="disabled">
            </div>
            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label">Province</label>
                <input type="text"
                       name="province"
                       class="contract-draft__input"
                       placeholder="Example: Ontario"
                       v-model="current.province"
                       disabled="disabled">
            </div>
            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label">Postal Code</label>
                <input type="text"
                       class="contract-draft__input"
                       placeholder="A1A 1A1"
                       v-model="current.postal_code"
                       disabled="disabled"
                >
            </div>
        </form>
    </div>
</template>
<script>
    import {mapGetters} from 'vuex'

    export default {

        data() {
            return {
                company_checkbox: '',
                validationState: false, // state of all validations if(true) then all input valid, else invalid
                currentEdit: false,
                type:'individual',
            }
        },
        props: [
            'current',
            'readOnly',
            'status_signed',
        ],
        computed: {
            changeUserInfoTooltip() {
                const usr_str = this.user.role === 'homeowner' ? 'Pro' : 'Ho';
                return `If you edit your details, ${usr_str} will have to approve the change of details`
            },
            ...mapGetters(["user"]),
        },
        methods: {
        },
    }
</script>
