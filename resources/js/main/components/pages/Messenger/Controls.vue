<template>
    <div class="chat__header hidden-sm-and-down" data-v-step="ms-1">

        <withdraw-dialog v-model="dialog_withdraw" :loading="loading" @withdraw="withdraw"/>

        <v-layout row wrap class="messenger-ho-9b">
            <div class="messeges-and-notes__tabs" data-v-step="ms-8">
                <v-btn flat
                       data-v-step="ms-2"
                       class="messeges-and-notes__btn left"
                       :class="{active: $route.name ==='messages'}"
                       @click="toMessages">
                    MESSENGER
                    <template v-if="chat.unread_messages_count !== 0 && typeof chat.unread_messages_count !== 'undefined'"><!-- if counter !== 0-->
                        <span class="msg-counter">
                            {{chat.unread_messages_count}}
                        </span>
                    </template>
                </v-btn>

                <v-btn flat
                       data-v-step="ms-3"
                       class="messeges-and-notes__btn right"
                       :class="{active: $route.name ==='notes'}"
                       @click="toNotes">
                    <a data-v-step="nt-1">NOTES</a>
                </v-btn>

                <div></div>
            </div>
            <v-spacer></v-spacer>

            <div class="chat-control__row" data-v-step="ms-9">
                <v-btn data-v-step="ms-4"
                        flat
                        class="chat-control__btn chat-control__file-history" @click="toFileHistory">
                    FILE HISTORY
                </v-btn>

                <v-btn flat
                       data-v-step="ms-5"
                       class="chat-control__btn chat-control__withdraw" @click="dialog_withdraw = true">
                    WITHDRAW
                </v-btn>

                <v-btn flat
                       data-v-step="ms-6"
                       class="ms-6as chat-control__btn  chat-control__create-contract"
                       @click="toContractDraft">
                    {{contractDraftButton}}
                </v-btn>
            </div>

        </v-layout>
    </div>
</template>
<script>
    import {mapGetters} from 'vuex'

    import WithdrawDialog from './dialogs/Withdraw'
    export default {
        components: {
            'withdraw-dialog': WithdrawDialog
        },
        data() {
            return {
                guarantee_project_id: this.$route.params.guarantee_project_id,
                loading: false,
            }
        },
        computed: {
            ...mapGetters(['user', 'contract_draft', 'chat', 'guarantee_project']),

            contractDraftButton() {
                if (this.guarantee_project.contract_status === 'signed') {
                    return 'CURRENT MILESTONE';
                }
                else if (this.guarantee_project.contract_status === 'finished' || this.guarantee_project.contract_status === 'completed') {
                    return 'SUMMARY';
                }

                else if(this.guarantee_project.pay_fee && this.user.role === 'contractor'){
                    return 'PAY FEE'
                }

                else if (this.guarantee_project.has_contract_draft) {
                    return 'PROJECT DRAFT';
                }
                else {
                    return 'CREATE PROJECT DRAFT'
                }
            },
            dialog_withdraw: {
                get() {
                    return this.$store.state.dialogChatWithdraw
                },
                set(val) {
                    this.$store.commit('set', {type: 'dialogChatWithdraw', data: val})
                }
            }
        },
        methods: {
            toNotes() {
                this.$router.push({
                    name: 'notes',
                    params: {guarantee_project_id: this.guarantee_project_id}
                })
            },
            toMessages() {
                this.$router.push({
                    name: 'messages',
                    params: {guarantee_project_id: this.guarantee_project_id}
                })
            },
            toFileHistory() {
                this.$router.push({
                    name: 'fileHistory',
                    params: {guarantee_project_id: this.guarantee_project_id}
                })
            },

            toContractDraft() {
                if (this.guarantee_project.contract_status === 'signed') {

                    return this.$router.push({
                        name: 'current-milestone',
                        params: {guarantee_project_id: this.guarantee_project_id}
                    })
                } else if (this.guarantee_project.contract_status === 'finished' || this.guarantee_project.contract_status === 'completed') {

                    return this.$router.push({
                        name: 'summary',
                        params: {guarantee_project_id: this.guarantee_project_id}
                    })
                } else if (this.guarantee_project.has_contract_draft) {

                    return this.$router.push({
                        name: 'current-draft',
                        params: {guarantee_project_id: this.guarantee_project_id}
                    })
                } else {

                    return this.$router.push({
                        name: 'new-draft',
                        params: {guarantee_project_id: this.guarantee_project_id}
                    });
                }
            },
            withdraw(comment) {
                this.loading = true;

                var params = {
                    id: this.guarantee_project.id,
                    comment: comment
                };

                this.$store.dispatch('withdrawContract', params).then(response => {
                    this.loading = false;
                    this.dialog_withdraw = false;
                    this.$router.push({name: 'projects-list'})
                });
            }
        }
    }
</script>
