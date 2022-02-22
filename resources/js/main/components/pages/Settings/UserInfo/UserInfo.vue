<template>
    <div class="contract-draft__gray-box contract-draft__gray-box_min-height contract-draft__personal-data">
        <form @submit.prevent="validateUserInformation">

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('first_name')}">
                    First name
                </label>
                <input :class="{'invalid-input':  errors.has('first_name'),'scroll__invalid-input':  errors.has('first_name') }"
                       class="contract-draft__input"
                       placeholder="First name"
                       v-validate="'required'"
                       data-vv-as="first name"
                       data-vv-name="first_name"
                       type="text"
                       v-model="setting.first_name"
                >
                <div v-if="errors.has('first_name')" class="invalid-message">
                    {{ errors.first('first_name') }}
                </div>
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('last_name')}">
                    Last name
                </label>
                <input :class="{'invalid-input':  errors.has('last_name'),'scroll__invalid-input':  errors.has('last_name') }"
                       class="contract-draft__input"
                       placeholder="Last name"
                       v-validate="'required'"
                       data-vv-as="last name"
                       data-vv-name="last_name"
                       type="text"
                       v-model="setting.last_name"
                >
                <div v-if="errors.has('last_name')" class="invalid-message">
                    {{ errors.first('last_name') }}
                </div>
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('email')}">
                    Email Address
                </label>
                <input :class="{'invalid-input':  errors.has('email'),'scroll__invalid-input':  errors.has('email') }"
                       class="contract-draft__input"
                       placeholder="Email Address"
                       v-validate="'required|email'"
                       data-vv-as="email"
                       data-vv-name="email"
                       type="text"
                       v-model="setting.email"
                >
                <div v-if="errors.has('email')" class="invalid-message">
                    {{ errors.first('email') }}
                </div>
            </div>

            <div class="contract-draft__input-box"  v-if="user.role === 'contractor'">
                <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('website')}">
                    Website <span class="gray-text">(Links will appear on business profile page)</span>
                </label>
                <input :class="{'invalid-input':  errors.has('website'),'scroll__invalid-input':  errors.has('website') }"
                       class="contract-draft__input"
                       placeholder="Website"
                       data-vv-as="website"
                       data-vv-name="website"
                       type="text"
                       v-model="setting.website_url"

                >
                <div v-if="errors.has('website')" class="invalid-message">
                    {{ errors.first('website') }}
                </div>
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('phone_number')}">
                    Phone Number
                </label>
                <input :class="{'invalid-input':  errors.has('phone_number'),'scroll__invalid-input':  errors.has('phone_number') }"
                       class="contract-draft__input"
                       placeholder="Phone"
                       v-validate="'required'"
                       data-vv-as="phone number"
                       data-vv-name="phone_number"
                       type="text"
                       v-model="setting.phone_number"
                >
                <div v-if="errors.has('phone_number')" class="invalid-message">
                    {{ errors.first('phone_number') }}
                </div>
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('street_name')}">
                    Street Name
                </label>
                <input :class="{'invalid-input':  errors.has('street_name'),'scroll__invalid-input':  errors.has('street_name') }"
                       class="contract-draft__input"
                       placeholder="Street Name"
                       data-vv-as="street name"
                       data-vv-name="street_name"
                       type="text"
                       v-model="setting.address"
                >
                <div v-if="errors.has('street_name')" class="invalid-message">
                    {{ errors.first('street_name') }}
                </div>
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('street_num')}">
                    Street Number
                </label>
                <input :class="{'invalid-input':  errors.has('street_num'),'scroll__invalid-input':  errors.has('street_num') }"
                       class="contract-draft__input"
                       placeholder="Street Number"
                       data-vv-as="street number"
                       data-vv-name="street_num"
                       type="text"
                >
                <div v-if="errors.has('street_num')" class="invalid-message">
                    {{ errors.first('street_num') }}
                </div>
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('unit_num')}">
                    Unit Number
                </label>
                <input :class="{'invalid-input':  errors.has('unit_num'),'scroll__invalid-input':  errors.has('unit_num') }"
                       class="contract-draft__input"
                       placeholder="Unit Number"
                       data-vv-as="unit number"
                       data-vv-name="unit_num"
                       type="text"
                >
                <div v-if="errors.has('unit_num')" class="invalid-message">
                    {{ errors.first('unit_num') }}
                </div>
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('city')}">
                    City
                </label>
                <input :class="{'invalid-input':  errors.has('city'),'scroll__invalid-input':  errors.has('city') }"
                       class="contract-draft__input"
                       placeholder="City"
                       v-validate="'required'"
                       data-vv-as="city"
                       data-vv-name="city"
                       type="text"
                       v-model="setting.city"
                >
                <div v-if="errors.has('city')" class="invalid-message">
                    {{ errors.first('city') }}
                </div>
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label" :class="{'invalid-input':  errors.has('postal_code')}">
                    Postal Code
                </label>
                <input :class="{'invalid-input':  errors.has('postal_code'),'scroll__invalid-input':  errors.has('postal_code') }"
                       class="contract-draft__input"
                       placeholder="Postal Code"
                       v-validate="'required|postal_code'"
                       v-mask="'A#A #A#'"
                       data-vv-as="postal code"
                       data-vv-name="postal_code"
                       type="text"
                       v-model="setting.postal_code"
                >
                <div v-if="errors.has('postal_code')" class="invalid-message">
                    {{ errors.first('postal_code') }}
                </div>
            </div>

            <template v-if="user.role === 'contractor'">
                <div class="contract-draft__input-box">
                    <label class="contract-draft__input-label">
                        Lead Radius*
                    </label>
                    <v-select
                            :items="radius"
                            v-model="setting.lead_radius"
                            label="Select Lead Radius"
                            solo
                    >

                    </v-select>
                </div>

                <div class="contract-draft__input-box">
                    <label class="contract-draft__input-label">
                    <span class="gray-text">
                        *The Lead Radius circle defines the territory where you wish to receive
                        leads.
                    </span>
                    </label>
                </div>
            </template>

        </form>
    </div>

</template>
<script>
    import {mapGetters} from 'vuex'
    import {ChildMixin} from "../../../common/Mixins/glValidate/mixins";

    export default {
        mixins: [ChildMixin],
        data() {
            return {
                validationState: false, // state of all validations if(true) then all input valid, else invalid

                current_radius: this.setting.lead_radius || 10,
            }
        },
        props: {
            setting: Object
        },
        computed: {
            ...mapGetters(["user"]),

            radius() {
                let arr = [];
                for (let i = 10; i <= 100; i += 10) {
                    arr.push(i);
                }
                return arr;
            },
        },
    }
</script>
