<template>
    <div  class="table-files">
        <div class="table-files__name-size-row">
            <div class="table-files__icon-name">
                <div class="img-position"><img :src="'/img/icon/chat-file.svg'" alt=""></div>
                {{ file.name }}
            </div>
            <div class="table-files__sizes">
                <span v-html="formatSize(file.size)"></span>
            </div>
        </div>
        <div class="table-files__link">
            <a  @click.prevent="downloadWithLoading(file.id)">
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
import dropzone from '../../../../mixins/dropzone/dropzone.js';
export default {
    mixins: [ dropzone],
    data(){
        return {
            loading:false,
        }
    },
    props:{
        file:Object,
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

