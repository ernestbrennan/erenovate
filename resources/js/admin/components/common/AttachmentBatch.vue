<template>
    <div>
        <template v-if="!batch.editing">
            <div class="file-table">
                <div :key="file.id" v-for="file in batch.files" class="file-table__row">
                    <div class="file-table__info">
                        <div class="file-table__title">
                            <img :src="'/img/icon/chat-file.svg'" alt="">{{ file.name }}
                        </div>
                        <div class="file-table__size">{{ file.size }}</div>
                    </div>
                    <div class="file-table__link">
                        <a @click.prevent="download(file.id)">DOWNLOAD</a>
                    </div>
                </div>
            </div>
            <div class="message-attch">
                <div class="message-attch__info-row">
                    <img :src="batch.user.photo" alt="img-attch">
                    <div class="message-attch__info">
                        {{ batch.user.firstname }}, {{ batch.created_at }}
                    </div>
                </div>
                <div class="message-attch__text">
                    {{ batch.description }}
                </div>
            </div>
            <div class="attch-history__controls">
                <button class="main-btn main-btn_border-red" @click="removeBatch(batch.id)">REMOVE</button>
                <button class="main-btn main-btn_border-blue" @click="editBatch(batch.id)" >EDIT</button>
            </div>
        </template>
        <template v-if="batch.editing">
            <div class="contract-draft__dropzone contract-draft__dropzone_editing">
                <div :key="file" v-for="file in batch.files" class="dz-preview dz-file-preview">
                    <div class="dz-inner-box">
                        <div class="dz-info">
                            <div class="dz-filename">
                                <div class="dz-progressbar__box" data-dz-uploadprogress="" style="width: 100%;">
                                    <img src="/img/icon/one-file_gray.svg" alt="file">
                                </div>
                                <span class="dz-filename__text" data-dz-name="">{{ file.name }}</span>
                            </div>
                            <div class="dz-size">
                                <span data-dz-size="">{{ file.size }}</span>
                            </div>
                        </div>
                        <div class="dz-remove-box">
                            <img src="/img/icon/close_gray.svg" alt="Click me to remove the file." data-dz-remove="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="contract-draft__dropzone-message">
                            <textarea class="contract-draft__textarea"
                                      placeholder="Comment to file (required)"
                                      @keyup="resizeTextarea"
                                      @keydown="resizeTextarea"
                                      v-model="batch.description">
                            </textarea>
                <div class="dropzone-message__btn-row">
                    <button class="main-btn main-btn_border-red"
                            @click="clearBatch(batch.id)">
                        CANCEL
                    </button>
                    <button class="main-btn main-btn_border-blue"
                            @click="postBatch(batch.id)">
                        POST ATTACHMENTS
                    </button>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        name: "attachmentBatch",
        props:{
            batch: Object
        },
        methods:{
            resizeTextarea(event) {
                event.target.style.height = 50 + 'px';
                event.target.style.height = (event.target.scrollHeight) + 'px';
            },
            editBatchShow(batch_id){
                this.batch.editing = true

            },
            editBatch(batch_id){
                this.editBatchShow(batch_id);
            },
        },
        created(){
        }
    }
</script>

<style scoped>

</style>
