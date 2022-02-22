<template>
    <messenger-content :type="type">

        <div v-if="!notes.length" class="chat__empty-messeges" :style="{'min-height': this.$store.state.chatBodyHeight + 'px'}">
            <div class="chat__empty-box">
                <img :src="'/img/no-notes.svg'" alt="">
                <h4 class="common-h4  nt-4-class text-xs-center">
                    Private Notes Area
                </h4>
                <p class="common-p text-xs-center" data-v-step="nt-3">
                    The notes area can be your central database to have all your relevant project information at your fingertips, all in one central place.
                    Easily view electronic copies of original documents, make notes of key project talking points, ideas, inspirational images, receipts, and personal notes to help make your project less stressful.
                    Or, you may find your own unique ways to use this free organizing tool.
                </p>
                <p v-if="subtitle" class="common-p text-xs-center">
                    All notes and files uploaded here are private and archived until your contract has been completed.
                </p>
            </div>
        </div>

        <div class="chat-message nt-4-class" data-v-step="nt-2" v-else  :key="note.id" v-for="note in notes">

            <message data-v-step="nt-3" :message="note" :user="user"/>

        </div>

        <template v-if="getTour">
            <v-tour name="tourNotes" :steps="steps" :options="optionsVueTour" :callbacks="tourCallbacks"></v-tour>
        </template>


    </messenger-content>
</template>

<script>
    import NotesTour from '../../../../plugins/tour/notes'
    import tourInit from '../../../../plugins/tour/init'


    import MessengerContent from '../MessengerContent.vue'
    import Message from '../Types/Message/Message.vue'

    import {mapGetters} from 'vuex'

    export default {
        mixins:[NotesTour,tourInit],
        components: {
            'messenger-content': MessengerContent,
            'message': Message,
        },
        data() {
            return {
                type: 'note',
                subtitle: true
            }
        },
        methods:{
            subtileFun(){
                if(window.innerWidth <=768){
                    this.subtitle = false
                }
            },
            scrollToBottom() {
                const timer = setTimeout(()=>{
                    $("#scrollBlockId").scrollTop($("#scrollBlockId")[0].scrollHeight);
                    clearTimeout(timer);
                },100)
            },
        },
        created() {
            console.log(1);
            this.subtileFun()
            this.$store.state.global_loader = true;
            this.$store.dispatch('getList', this.$route.params.guarantee_project_id).then(response => {
                this.$store.state.global_loader = false;
                this.scrollToBottom();
            })

        },
        mounted(){
            this.scrollToBottom();
        },
        computed: {
            ...mapGetters(["user", "notes"])
        },
        beforeDestroy() {
            this.$store.commit('set', {
                type: 'notes',
                data: []
            });
        },
    }
</script>
