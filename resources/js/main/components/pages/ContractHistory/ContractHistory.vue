<template>
    <div class="contract-comp contract-comp_history">
        <div class="contract-comp__header hidden-sm-and-down">
            <div class="g-flex g-flex_row g-flex_wrap margin-10">
                <div class="contract-header__title hidden-sm-and-down">
                    Drafts History
                </div>
                <v-spacer class="hidden-sm-and-down"></v-spacer>
                <button class="main-btn main-btn_full-blue" @click="toButton">
                    {{contract_draft_button_text}}
                </button>
            </div>
        </div>
        <div :style="{height: component_container_height}" class="contract-comp__body component-body_scroll scrollbar">
            <div class="contract-history">

                <div class="contract-history__draft" v-for="history in draft_history">
                    <notification v-for="message in history" :key="message.id" :message="message" />
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import SystemNotification from './SystemNotification'
    import {mapGetters} from 'vuex'

    export default {
        components: {
            'notification': SystemNotification
        },
        data() {
            return {
                windowHeight: false,
            }
        },
        computed: {
            ...mapGetters(["draft_history", "guarantee_project"]),

            contract_draft_button_text() {
                if (this.guarantee_project.contract_status === 'signed') {
                    return 'CURRENT MILESTONE';
                }
                else if (this.guarantee_project.contract_status === 'finished' || this.guarantee_project.contract_status === 'completed') {
                    return 'SUMMARY';
                }
                else if (this.guarantee_project.has_contract_draft) {
                    return 'CURRENT VERSION';
                }
                else {
                    return 'MESSENGER';
                }
            },
            component_container_height(){
                let width = window.innerWidth;
                let height = this.$store.state.containerHeight;
                let value = height - 65;
                if (width >= 992) {
                    return value + 'px'
                } else {
                    return height + 'px'
                }

            }
        },
        methods: {
            toButton() {
                let targetId = this.guarantee_project.id;

                if (this.guarantee_project.contract_status === 'signed') {

                    return this.$router.push({
                        name: 'current-milestone',
                        params: {guarantee_project_id: targetId}
                    })
                }
                else if (this.guarantee_project.contract_status === 'finished' || this.guarantee_project.contract_status === 'completed') {

                    return this.$router.push({
                        name: 'summary',
                        params: {guarantee_project_id: targetId}
                    })
                }
                else if (this.guarantee_project.has_contract_draft) {

                    return this.$router.push({
                        name: 'current-draft',
                        params: {guarantee_project_id: targetId}
                    })
                } else {

                    return this.$router.push({
                        name: 'messages',
                        params: {guarantee_project_id: targetId}
                    });
                }
            },

        },
        created() {
            this.$store.state.global_loader = true;
            this.$store.dispatch('getDraftHistory', this.$route.params.guarantee_project_id).then(response => {
                this.$store.state.global_loader = false
            });
        }
    }
</script>
