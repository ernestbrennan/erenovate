import Vue from "vue";
import VeeValidate, {Validator} from "vee-validate";
import Vuelidate from "vuelidate";

Vue.use(Vuelidate);

Vue.use(VeeValidate, {
    events: 'blur|change',
});

Validator.extend('postal_code', {
    getMessage(field, params, data) {
        return (data && data.message) || 'Postal code invalid'
    },
    validate(value) {
        let regex = /^[a-zA-Z][0-9][a-zA-Z] [0-9][a-zA-Z][0-9]$/
        return regex.test(value)
    }
});
Validator.extend('formatted_price', {
    getMessage(field, params, data) {
        return (data && data.message) || 'Price invalid'
    },
    validate(value) {
        return formatPrice(value) > 0;
    }
});
Validator.extend('earlier_date', {
    getMessage(field, val) {
        return 'Start date bigger then end date'
    },
    validate (value, [otherValue]) {
        if(otherValue === null||otherValue.length === 0){
            return false
        }
        let startParts = otherValue.split('-');
        let endParts = value.split('-');
        let start = new Date(startParts[0], startParts[1] -1, startParts[2]) // month is 0-based
        let end = new Date(endParts[0], endParts[1] -1, endParts[2]);

        return end > start
    },
},{hasTarget: true});
