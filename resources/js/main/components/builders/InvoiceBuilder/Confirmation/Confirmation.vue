<template>
    <div class="contract-draft__el-box">
        <div class="contract-draft__title-box">
            <div class="contract-draft__title" @click="openSection">
                Confirmation
            </div>
            <div class="contract-draft__el-controls">
                <img :src="'/img/icon/caret-icon_gray.svg'"
                     class="contract-draft__curret"
                     :class="{active : openstate}"
                >
            </div>
        </div>
        <div ref="summary_box"
             class="contract-draft__slide-main-box contract-draft__gray-box_invoice contract-draft__gray-box contract-draft__gray-box_full">
            <div class="contract-draft__file-attch-box">
                <div class="contract-draft__file-attch-history">
                    <div :key="batch.id"
                         v-for="batch in batches"
                         :class="{'attch-history_disabled': readOnly}"
                         class="attch-history">
                        <div class="file-table">
                            <div :key="file.id" v-for="file in batch.files" class="file-table__row">
                                <div class="file-table__info">
                                    <div class="file-table__title">
                                        <div class="file-table__title-img"><img :src="'/img/icon/chat-file.svg'" alt="">
                                        </div>
                                        <div class="file-table__title-text">{{ file.name }}</div>
                                    </div>
                                    <div class="file-table__size">{{ file.size|formatSize }}</div>
                                </div>
                                <div class="file-table__link">
                                    <a @click.prevent="download(file.id)" v-html="downloadInner"></a>
                                </div>
                            </div>
                        </div>
                        <div class="message-attch">
                            <div class="message-attch__info-row">
                                <img :src="batch.user.photo" alt="img-attch">
                                <div class="message-attch__info">
                                    {{ batch.user.firstname }}, {{ batch.created_at | moment("MMM D h:mm:ss A") }}
                                </div>
                            </div>
                            <div class="message-attch__text">
                                {{ batch.description }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {

        data() {
            return {
                openstate: true,
            }
        },
        props: {
            batches: Array,
        },
        filters: {
            formatSize: function (bytes) {
                if (bytes >= 1073741824) {
                    bytes = (bytes / 1073741824).toFixed(2) + " GB";
                } else if (bytes >= 1048576) {
                    bytes = (bytes / 1048576).toFixed(2) + " MB";
                } else if (bytes >= 1024) {
                    bytes = (bytes / 1024).toFixed(2) + " KB";
                }
                return bytes;
            }
        },

        computed: {
            downloadInner() {
                return (window.innerWidth >= 768) ? 'DOWNLOAD' : `<img src="/img/icon/download-mobile-chat_blue.svg" alt="DOWNLOAD">`
            },
        },
        methods: {
            openSection() {
                this.openstate = !this.openstate;
                $(this.$refs.summary_box).slideToggle();
            },
        },
        created() {
        },

    }
</script>

<style>

</style>
