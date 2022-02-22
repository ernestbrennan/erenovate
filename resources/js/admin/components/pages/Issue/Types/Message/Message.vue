<template>
    <div>
        <div class="chat-message__row" :class="'chat-message__' + user.role">

            <div class="chat-message__avatar">
                <img class="human" :src="user.photo" alt="avatar" @click="toProfile">
            </div>

            <div class="chat-message__text-box">

                <h5>{{ user.firstname }} , {{ message.created_at | moment("MMM D h:mm:ss A")}}</h5>

                <div class="table-data__wr">
                    <div :key="index" v-for="(value, index) in message.files" class="table-files">
                        <div class="table-files__name-size-row">
                            <div class="table-files__icon-name">
                                <div class="img-position"><img :src="'/img/icon/chat-file.svg'" alt=""></div>
                                {{ value.name }}
                            </div>
                            <div class="table-files__sizes">{{ value.size|formatSize }}</div>
                        </div>
                        <div class="table-files__link">
                            <a @click.prevent="download(value.id)" v-html="downloadLink"></a>
                        </div>
                    </div>
                    <p v-html="messageText"></p>
                </div>

            </div>
        </div>
        <template v-for="url in urls">
            <external-link :urlHost="extractHostname(url)" :user="user" :url="url"></external-link>
        </template>
    </div>
</template>
<script>

    import ExternalLink from './ExternalLink';

    export default {
        components: {
            'external-link': ExternalLink
        },
        data() {
            return {
                urls: this.message.content.match(this.getRegex())
            }
        },
        props: [
            'message',
            'user'
        ],
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
            downloadLink() {
                if (window.innerWidth >= 768) {
                    return 'DOWNLOAD'
                }
                return `<img src="/img/icon/download-mobile-chat_blue.svg" alt="">`
            },
            messageText() {
                return this.message.content.replace(this.getRegex(), function (url) {
                    return '<a target="_blank" href="' + url + '">' + url + '</a>';
                });
            }
        },
        methods: {
            extractHostname(url) {
                let hostname, site;
                const http = url.split('://')[0];
                if (url.indexOf("//") > -1) {
                    hostname = url.split('/')[2];
                } else {
                    hostname = url.split('/')[0];
                }
                hostname = hostname.split(':')[0];
                hostname = hostname.split('?')[0];
                site = http + '://' + hostname;
                return site;
            },
            download(file_id) {
                api({
                    method: 'post',
                    url: '/api/file.download',
                    data: {
                        file_id: file_id
                    },
                    responseType: 'blob'
                })
                    .then(response => {

                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(response.data);

                        a.href = url;
                        a.download = response.headers['file-name'];
                        a.click();

                        window.URL.revokeObjectURL(url);
                    });

            },
            getRegex() {
                return new RegExp("https?:\\/\\/(www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{2,256}\\.[a-z]{2,6}\\b([-a-zA-Z0-9@:%_\\+.~#?&//=]*)", "g");
            },
            toProfile() {
                if(this.user.role === 'admin'){ return false }
                window.open(
                    this.user.profile_link,
                    '_blank'
                );
            }
        }
    }
</script>


