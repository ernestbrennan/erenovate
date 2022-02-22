<template>
    <div>
        <template v-if="$route.name === 'summary'">
            <div class="summary-time__link"
                 @click="to">
                VIEW
            </div>
        </template>

        <template v-else-if="$route.name === 'messages'">
            <v-btn depressed
                   color="#1875F0"
                   dark
                   class="chat-message__btn-detail"
                   @click="to">
                VIEW DETAILS
            </v-btn>
        </template>

        <template v-else-if="$route.name === 'contract-history'">
            <button class="contract-history__view main-btn main-btn_full-blue hidden-md-and-down"
                    @click="to">
                VIEW
            </button>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        data() {
            return {
                types: {
                    contract_draft_offered: 'contract_draft_offered',
                    contract_draft_rejected: 'contract_draft_rejected',
                    contract_draft_proposed: 'contract_draft_proposed',
                },
            }
        },
        props: {
            notification: Object,
        },
        computed: {
            ...mapGetters(["guarantee_project"])
        },
        methods: {
            to() {
                const type = this.notification.type;
                const types = this.types;
                const id = this.$route.params.guarantee_project_id;

                if (this.notification.type === this.types.contract_draft_proposed || this.notification.type === this.types.contract_draft_rejected) {

                    return this.$router.push({
                        name: 'history-draft',
                        params: {
                            guarantee_project_id: id,
                            draft_version: this.notification.contract_draft.version
                        }
                    });

                }
                else if (this.guarantee_project.contract_status !== 'pending') {

                    return this.$router.push({
                        name: 'contract-signed',
                        params: {guarantee_project_id: id}
                    });
                }

                else if (type === types.contract_draft_offered) {

                    return this.$router.push({
                        name: 'current-draft',
                        params: {guarantee_project_id: id}
                    });

                }
            }
        }
    }
</script>