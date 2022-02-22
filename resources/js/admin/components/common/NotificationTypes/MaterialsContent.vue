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
    export default {
        data() {
            return {
                types: {
                    materials_content_edited: 'materials_content_edited',
                    materials_content_accepted: 'materials_content_accepted',
                    materials_content_rejected: 'materials_content_rejected',
                },
            }
        },
        props: {
            notification: Object,
        },
        methods: {
            to(){

            }
        }
    }
</script>
