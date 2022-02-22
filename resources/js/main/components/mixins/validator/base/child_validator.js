export default {
    inject: ['parentValidator'],
    created() {
        this.$validator = this.parentValidator
    },
    methods: {
        clearErrors(name){
            const field = this.$validator.fields.find({name:name});
            field.reset();
            this.$validator.errors.remove(field.name, field.scope);
        },
    },
};