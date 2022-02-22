<template>
    <div>
        <input :placeholder="placeholder"
               class="contract-draft__input"
               type="text"
               :data-vv-as="vee_as"
               :class="inputclass"
               v-model.lazy="main_value"
               @focus="clearErrors"
               :disabled="readOnly"
               v-validate="'required'"
               :name="label"
               :key="label"
               >
        <div v-if="error" class="invalid-message">
           {{ error }}
        </div>
        <div v-if="false" class="old-version-message">
            Previus value : {{ previous_value }}
        </div>
    </div>

</template>
<script>

export default {
    name: "SimpleInput",
    $_veeValidate: {
        name () {
            return this.label;
        },
        value () {
            return this.value;
        }
    },
    data(){
        return {

        }
    },
    props: {
        value: [String, Boolean],
        readOnly: Boolean,
        previous_value:{
            default: false,
        },
        label: {
            type: String
        },
        vee_as:{
            type:String,
        },
        placeholder:{
            type: String,
        },
        error: {
            type: String,
            required: false
        },
    },
    computed:{
        inputclass(){
            if(!!this.error){
                return 'invalid-input scroll__invalid-input'
            } else if ( this.previous_value !== false && this.previous_value.length > 0) {
                return 'changed-input'
            }
        },
        main_value: {
            get() {
                return this.value
            },
            set(value) {
                this.$emit('input', value)
            }
        },
    },
    methods:{
        clearErrors(){
            const field = this.$validator.fields.find({name:this.label});
            field.reset();
            this.$validator.errors.remove(field.name, field.scope);
        },
    },
}
</script>
