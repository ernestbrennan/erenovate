<template>
   <div class="header-controls hidden-md-and-up">
        <template v-if="this.$route.name === 'projects-list'">
            <div>
                <button
                class="header-controls__search-button"
                :class="{ active: slideMenu}"
                @click="toggleSlideMenu"
                >
                    <img :src="iconShows" alt="">
                </button>
            </div>
        </template>
        <template v-else-if="this.$route.name === 'notes' || this.$route.name === 'messages'">
            <menu-header>
                <chat-btns></chat-btns>
            </menu-header>
        </template>
       <template v-else-if="this.$route.name === 'fileHistory'">
           <img class="header-controls__single-icon" @click="toMessages" src="/img/icon/messeges_gray.svg" alt="tomesseges">
       </template>
       <template v-else-if="this.$route.name === 'new-draft'">
           <menu-header>
               <contract-btns></contract-btns>
           </menu-header>
       </template>
       <template v-else-if="this.$route.name === 'history-draft'">
           <menu-header>
                <contract-history></contract-history>
           </menu-header>
       </template>
       <template v-else-if="this.$route.name === 'contract-history'">
           <img class="header-controls__single-icon" @click="toCurrentDraft" src="/img/icon/contract-history-header.svg" alt="to_current">
       </template>
       <template v-else-if="this.$route.name === 'current-draft'">
           <menu-header>
               <contract-current></contract-current>
           </menu-header>
       </template>
       <template v-else-if="this.$route.name === 'contract-signed'">
           <menu-header>
               <contract-signed></contract-signed>
           </menu-header>
       </template>
       <template v-else-if="this.$route.name === 'list-invoice'">
           <img class="header-controls__single-icon" @click="toMessages" src="/img/icon/invoiceheader_gray.svg" alt="to_current">
       </template>
       <template v-else-if="this.$route.name === 'history-invoice'">
           <menu-header>
                       <history-invoice></history-invoice>
           </menu-header>
       </template>
       <template v-else-if="this.$route.name === 'create-invoice'">
           <menu-header>
               <create-invoice></create-invoice>
           </menu-header>
       </template>
       <template v-else-if="this.$route.name === 'current-invoice'">
           <menu-header>
               <current-invoice></current-invoice>
           </menu-header>
       </template>
       <template v-else-if="this.$route.name === 'by-id-milestone'">
           <menu-header>
               <in-progress-milestone></in-progress-milestone>
           </menu-header>
       </template>
       <template v-else-if="this.$route.name === 'current-milestone'">
           <menu-header>
               <in-progress-milestone></in-progress-milestone>
           </menu-header>
       </template>
       <template v-else-if="this.$route.name === 'edited-milestone'">
           <menu-header>
               <milestone-editing></milestone-editing>
           </menu-header>
       </template>
       <template v-else-if="this.$route.name === 'summary'">
           <menu-header>
               <summary-ctr
                   :role="this.user.role"
               ></summary-ctr>
           </menu-header>
       </template>
       <template v-else-if="this.$route.name === 'issue'">
           <menu-header>
               <issue-controls></issue-controls>
           </menu-header>
       </template>

   </div>
</template>
<script>
    import menuSlot from './MobileBtns/VMenuControll'
    import contractBtns from './MobileBtns/ContractBtns'
    import chatBtns from './MobileBtns/ChatBtns'
    import contractHistory from './MobileBtns/ContractHistory'
    import contractCurrent from './MobileBtns/CurrentContract'
    import contractSigned from './MobileBtns/ContractSigned'
    import historyInvoice from './MobileBtns/HistoryInvoice'
    import createInvoice from './MobileBtns/CreateInvoice'
    import currentInvoice from './MobileBtns/CurrentInvoice'
    import milestoneComplete from './MobileBtns/MiestoneComplete'
    import milestoneInProgress from './MobileBtns/MilestoneInProgress'
    import milestoneEditing from './MobileBtns/MilestoneEditing'
    import SummaryCrt from './MobileBtns/SummaryCtr'
    import IssueControl from './MobileBtns/IssueControls'
    import {mapGetters} from 'vuex'
    export default {
    data(){
        return{
        }
    },
    components:{
        'menu-header': menuSlot,
        'contract-btns': contractBtns,
        'chat-btns': chatBtns,
        'contract-history': contractHistory,
        'contract-current': contractCurrent,
        'contract-signed': contractSigned,
        'history-invoice': historyInvoice,
        'create-invoice': createInvoice,
        'current-invoice': currentInvoice,
        'milestone-complete': milestoneComplete,
        'in-progress-milestone': milestoneInProgress,
        'milestone-editing': milestoneEditing,
        'summary-ctr': SummaryCrt,
        'issue-controls': IssueControl,
    },
    computed:{
        ...mapGetters(["guarantee_project", "user"]),
        iconShowsChat(){
            if(this.slideMenu === false){
                return '/img/icon/options_gray.svg'
            } else {
                return '/img/icon/close_white.svg'
            }
        },
        iconShows(){
            if(this.slideMenu === false){
                return '/img/icon/search_blue.svg'
            } else {
                return '/img/icon/close_white.svg'
            }
        },
        slideMenu: {
                get() {
                    return this.$store.state.slideSearchContracts
                },
                set(val) {
                    this.$store.commit('set', { type: 'slideSearchContracts', data :val})
                }
        },
        dialogChatWithdraw:{
            get(){
                return this.$store.state.dialogChatWithdraw
            },
            set(val){
                this.$store.commit('set', {type: 'dialogChatWithdraw', data: val})
            }
        }
    },
    methods:{
        toggleSlideMenu(){
            this.slideMenu = !this.slideMenu
        },
        toMessages() {
            let targetId = this.$route.params.guarantee_project_id;
            this.$store.state.slideSearchContracts = false;
            this.$router.push({
                name: 'messages',
                params: {guarantee_project_id: targetId}
            })
        },
        toCurrentDraft() {
                let targetId = this.$route.params.guarantee_project_id;
                if (this.guarantee_project.contract_status === 'signed') {
                    return this.$router.push({
                        name: 'contract-signed',
                        params: {guarantee_project_id: targetId}
                    })
                }
                if (this.guarantee_project.has_contract_draft) {

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
}
</script>
