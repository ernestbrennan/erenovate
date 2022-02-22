export default {
    props: ['value'],
    computed: {
        show: {
            get() {
                return this.value
            },
            set(value) {
                this.$emit('input', value)
            }
        },
    },
    methods: {
        close() {
            this.show = false;
        },
    },
}