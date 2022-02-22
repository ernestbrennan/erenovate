<template>
    <div>
        <div class="chat-message__row" :class="'chat-message__' + user.role">

            <div class="chat-message__avatar ms-10-class">
                <img class="human" :src="user.photo" alt="avatar" @click="toProfile">
                <span class="chat-message__avatar-status" :class="chat_status"></span> <!-- class mod : online , last-seen --->
            </div>

            <div class="chat-message__text-box">

                <h5>{{ user.firstname }} ,
                    {{ message.created_at | moment("MMM D h:mm A")}}
                    <div class="chat-message__isread-icon" v-if="message.receiver_seen"> (read)</div>
                    <div class="chat-message__notread-icon" v-else></div>
                </h5>

                <div class="table-data__wr">
                    <file-row :key="index" v-for="(file, index) in message.files" :file="file"></file-row>
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
    import read_message from '../../../../mixins/chat/read_message';
    import FileRow from './FileRow.vue'

    export default {
        mixins: [read_message],
        components: {
            'external-link': ExternalLink,
            'file-row': FileRow
        },
        data() {
            return {
                urls: this.messageContent,
                chat_status: this.user.chat_status,
                interval: setInterval(() => {
                    this.chat_status = this.user.chat_status
                }, 1000)
            }
        },
        props: [
            'message',
            'user'
        ],
        computed: {
            messageContent(){
                return this.message.content || '';
            },
            messageText() {
                return  this.messageContent.replace(this.getRegex(), function (url) {
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
            getRegex() {
                return new RegExp("https?:\\/\\/(www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{2,256}\\.[a-z]{2,6}\\b([-a-zA-Z0-9@:%_\\+.~#?&//=]*)", "g");
            },
            toProfile() {
                return false
                window.open(
                    this.user.profile_link,
                    '_blank'
                );
            }
        },
        beforeDestroy(){
            clearInterval(this.interval)
        }
    }
</script>


