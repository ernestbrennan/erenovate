<template>
    <div class="contract-draft__gray-box contract-draft__gray-box_min-height" :log="log">
        <h3 class="contract-draft__box-title">PRO Contact Information</h3>
        <div class="contract-draft__contractor-inputs" data-v-step="ccd-5">
            <template v-if="interlocutor.type === 'individual'">

                <div class="contract-draft__input-box">
                    <label class="contract-draft__input-label">First Name</label>
                    <input type="text"
                           class="contract-draft__input"
                           placeholder="Example: John"
                           v-model="interlocutor.first_name"
                           disabled>
                </div>

                <div class="contract-draft__input-box">
                    <label class="contract-draft__input-label">Last Name</label>
                    <input type="text"
                           class="contract-draft__input"
                           placeholder="Example: Jones"
                           v-model="interlocutor.last_name"
                           disabled>
                </div>
            </template>
            <template v-if="interlocutor.type === 'legal'">

                <div class="contract-draft__input-box">
                    <label class="contract-draft__input-label">Company Name</label>
                    <input type="text"
                           class="contract-draft__input"
                           placeholder="Example: Erenovate LLC"
                           v-model="interlocutor.company_name"
                           disabled>
                </div>

                <div class="contract-draft__input-box">
                    <label class="contract-draft__input-label">Name of Representative</label>
                    <input type="text"
                           class="contract-draft__input"
                           placeholder="Example: John Doe"
                           v-model="interlocutor.representative_name"
                           disabled>
                </div>
            </template>

            <div class="contract-draft__sep-title">Address</div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label">Address Line 1</label>
                <input type="text"
                       class="contract-draft__input"
                       placeholder="Example: 1 Main St. SE"
                       v-model="interlocutor.address_1"
                       disabled>
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label">Address Line 2</label>
                <input type="text"
                       class="contract-draft__input"
                       placeholder="Example: App. 123"
                       v-model="interlocutor.address_2"
                       disabled>
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label">City</label>
                <input type="text"
                       class="contract-draft__input"
                       placeholder="Example: Toronto"
                       v-model="interlocutor.city"
                       disabled>
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label">Province</label>
                <input type="text"
                       class="contract-draft__input"
                       placeholder="Example: Ontario"
                       v-model="interlocutor.province"
                       disabled>
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label">Postal Code</label>
                <input type="text"
                       class="contract-draft__input"
                       placeholder="A1A 1A1"
                       v-model="interlocutor.postal_code"
                       disabled>
            </div>
        </div>
    </div>
</template>
<script>
    import {mapGetters} from 'vuex'

    export default {
        data() {
            return {
                entityType: false,
                company_checkbox: '',
                validationState: false, // state of all validations if(true) then all input valid, else invalid
                currentEdit: false
            }
        },
        props: [
            'interlocutor',
            'status_signed'
        ],
        methods: {
        },
        filters: {
            capitalize: (value) => {
                value = value.toString();
                return value.charAt(0).toUpperCase() + value.slice(1)
            }

        },
        computed: {
            ...mapGetters(["user"]),
            userContractText() {
                if (this.user.role === 'homeowner') {
                    return 'Contractor Information'
                } else {
                    return 'Client Information'
                }
            },
            log() {
                if (this.interlocutor.user.userId) console.log(this.interlocutor.user.userId);

                return true;
            }
        },
    }
</script>
