<template>
    <div class="contract-draft__input_dropdown">
        <template v-if="readOnly">
            <input disabled="disabled"
                   placeholder="MM-DD-YY"
                   type="text"
                   :class="inputClassList"
                   v-model.lazy="date"
                   class="contract-draft__input">
            <div v-if="error" class="invalid-message">
                {{ error }}
            </div>
            <div v-else-if="is_previous_changed" class="old-version-message">
                Previous value: {{ previous_value }}
            </div>
        </template>
        <template v-else-if="isValidate !== false">
            <input :disabled="isDisabled"
                   placeholder="MM-DD-YY"
                   type="text"
                   v-model.lazy="date"
                   :class="inputClassList"
                   @focus="toggleDateTimepicker"
                   class="contract-draft__input"
            >
            <div v-if="error" class="invalid-message">
                    {{ error }}
            </div>
            <div v-else-if="is_previous_changed" class="old-version-message">
                Previous value: {{ previous_value }}
            </div>
        </template>
        <template v-else>
            <input :disabled="isDisabled"
                   placeholder="MM-DD-YY"
                   type="text"
                   v-model.lazy="date"
                   @focus="toggleDateTimepicker"
                   :class="inputClassList"
                   class="contract-draft__input">
        </template>

        <v-date-picker v-if="isOpen"
                       @dblclick.native="changeTime"
                       v-model.lazy="date"
                       :min="getMinDate"
                       no-title scrollable>
            <v-spacer></v-spacer>
            <v-btn flat color="primary" @click="close">Cancel</v-btn>
            <v-btn flat color="primary" @click="changeTime">OK</v-btn>
        </v-date-picker>
    </div>
</template>

<script>
    import {minDate} from '../Mixins/datepicker.js'

    export default {
        name: "DatePicker",
        mixins: [minDate],
        $_veeValidate: {
            name () {
                return this.label;
            },
            value () {
                return this.value;
            }
        },
        data() {
            return {
                isOpenState: false,
                isDisabled: false,
            }
        },
        props: {
            value: [String, Boolean],
            isValidate: [String, Boolean],
            readOnly: Boolean,
            setMinDate: String,
            label: {
                type: String
            },
            error: {
                type: String,
                required: false
            },
            is_previous_changed:{
                type: Boolean,
                required: false,
                default: false
            },
            previous_value: {
                type: String,
                required: false
            }
        },
        computed: {
            getMinDate(){
                if(this.setMinDate){
                    return this.minDateEnd(this.setMinDate)
                }
                return this.minDate;
            },
            inputClassList(){
                if(!!this.error){
                    return 'invalid-input scroll__invalid-input'
                } else if (this.is_previous_changed) {
                    return 'changed-input'
                }
            },
            isOpen:{
                get() {
                    return this.isOpenState
                },
                set(value) {
                    this.$emit('isOpen', value)
                    this.isOpenState = value;
                    return value;
                }
            },
            date: {
                get() {
                    return this.value
                },
                set(value) {
                    this.$emit('input', value)
                }
            },
        },
        methods: {

            close(){
                this.isOpen = false;
                this.isDisabled = false;
            },
            datePickerDisabled() {
                if (this.readOnly === true) {
                    return true
                }
                return this.isOpen;
            },
            changeTime() {
                this.$emit('blur')
                this.date = this.formatDate(this.date)
                this.isDisabled = !this.isDisabled
                this.isOpen = !this.isOpen
                this.$emit('datePicked', this.date)
            },
            toggleDateTimepicker() {
                this.date = this.parseDate(this.date)
                this.isOpen = !this.isOpen
                this.isDisabled = !this.isDisabled;
                this.$emit('focus');
            },
            formatDate(date) {
                // if (!date) return null
                // const [year, month, day] = date.split('-')
                // return `${month}-${day}-${year}`
                return date
            },
            parseDate(date) {
                // if (!date) return null
                // const [month, day, year] = date.split('-')
                // return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
                return date
            },
        },
    }
</script>
