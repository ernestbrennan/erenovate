<template>
    <div ref="contractHeader" class="contract-comp__header">
        <div class="g-flex g-flex_row g-flex_wrap margin-10 g-flex_vertical-center">
            <div class="contract-header__title hidden-sm-and-down">
                Project Scope
            </div>
            <v-spacer class="hidden-sm-and-down"></v-spacer>
            <v-spacer class="hidden-md-and-up"></v-spacer>
        </div>
    </div>
</template>
<script>
    import {mapGetters} from 'vuex'

    export default {
        data() {
            return {
                guarantee_project_id: this.$route.params.guarantee_project_id
            }
        },
        computed: {
        },
        methods: {
        },
    }
</script>
