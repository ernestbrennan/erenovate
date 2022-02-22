<template>
    <div class="file-table__row">
        <div class="file-table__info">
            <div class="file-table__title">
                <div class="file-table__title-img"><img src="/img/icon/chat-file.svg" alt=""></div>
                    <div class="file-table__title-text">{{ file.name| cropName }}</div>
                </div>
                <div class="dz-size file-table__size">
                    <span v-html="formatSize(file.size)"></span>
                </div>
            </div>
            <div class="file-table__link">
                <a @click.prevent="downloadWithLoading(file.id)" >
                    <template v-if="is_mobile">
                        <template v-if="loading">
                            <v-progress-circular
                                indeterminate
                                color="primary"
                            ></v-progress-circular>
                        </template>
                        <template v-else>
                            <img src="/img/icon/download-mobile-chat_blue.svg" alt="">
                        </template>
                    </template>
                    <template v-else>
                        DOWNLOAD
                    </template>
                </a>
            </div>
        </div>
</template>

<script>

import dropzone from '../../../components/mixins/dropzone/dropzone.js'
export default {
    mixins: [dropzone],
    data(){
        return {
            loading: false,
        }
    },
    props:{
        file: Object
    },
    filters: {
        cropName: function(string_name){
            if(string_name.length> 30){
                return string_name.substring(0, 30) + '...'
            }
            return string_name
        }
    },
    computed:{
        is_mobile(){
            if (window.innerWidth >= 768) {
                return false
            }
            return true
        },
    },

}
</script>

<style>

</style>

