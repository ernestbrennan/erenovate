<template>
    <div class="contract-comp">
        <controls/>
        <contract-builder :readOnly="false" :contract_draft="contract_draft" :contract="contract"/>
    </div>
</template>

<script>
    import Controls from "./Controls"
    import ContractBuilder from "../../builders/ContractBuilder/ContractBuilder"

    import {ParentMixin} from "../../common/Mixins/glValidate/mixins";
    import {mapGetters} from 'vuex'

    export default {
        mixins: [ParentMixin],
        components: {
            'controls': Controls,
            'contract-builder': ContractBuilder,
        },
        created() {

            this.$store.dispatch('clearContract');
            this.$store.dispatch('clearContractDraft');

            if (this.$route.params.archived_draft_id) {
                this.getArchivedDraft()
            } else {
                this.getUserInfo()
            }

        },

        computed: {
            ...mapGetters(["contract_draft", "contract", "guarantee_project"]),
        },

        methods: {
            getUserInfo() {
                this.$store.state.global_loader = true;

                api.get('user.getInfo').then(response => {

                    this.$store.state.global_loader = false;
                    this.$store.state.contract.current_user_info = response.data;
                });
            },
            getArchivedDraft() {
                this.$store.state.global_loader = true;

                api.get('archived-draft.getById', {
                    params: {id: this.$route.params.archived_draft_id}
                }).then(response => {

                    let draft = response.data.archived_draft.draft;

                    this.$store.commit('set', {type: 'contract', data: draft.contract});
                    this.$store.commit('set', {type: 'contract_draft', data: draft.contract_draft});

                    this.$store.state.global_loader = false;

                }).catch(err => {

                    return this.$router.push({name: 'invite-ho'});
                });
            }
        }
    }
</script>