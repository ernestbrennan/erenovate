import {find, propEq} from 'ramda'
import bus from './errorBus'

export const ChildMixin = {
    inject: ['parentValidator'],
    mounted() {
        //Listen on the bus for the parent component running validation
        bus.$on('validate', this.onValidate)
        //Watch for the changes to the childs error bag and pass back to the parent
        this.$watch(() => this.errors.errors, (newValue, oldValue) => {
            const newErrors = newValue.filter(error =>
                find(propEq('field', error.field))(oldValue) === undefined
            )
            const oldErrors = oldValue.filter(error =>
                find(propEq('field', error.field))(newValue) === undefined
            )
            bus.$emit('errors-changed', newErrors, oldErrors)
        })
    },
    created() {
        this.$validator = this.parentValidator
    },
    methods: {
        onValidate() {
            this.$validator.validateAll();
            if (this.errors.any()) {
                bus.$emit('errors-changed', this.errors.errors)
            }
        },
    },
    beforeDestroy() {
        //When destroying the element remove the listeners on the bus.
        //Useful for dynaically adding and removing child components
        bus.$emit('errors-changed', [], this.errors.errors)
        bus.$off('validate', this.onValidate)
    },
};
export const ParentMixin = {
    provide() {
        return {parentValidator: this.$validator}
    },
    mounted() {
        //Emit that validation is required on the bus
        this.$on('veeValidate', () => {
            bus.$emit('validate');
        })
        //Listen on the bus for changers to the child components error bag and merge in/remove errors
        bus.$on('errors-changed', (newErrors, oldErrors) => {
            newErrors.forEach(error => {
                if (!this.errors.has(error.field)) {
                    this.errors.add(error.field, error.msg)
                }
            })
            if (oldErrors) {
                oldErrors.forEach(error => {
                    this.errors.remove(error.field)
                })
            }
        })
    },
};

