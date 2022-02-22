<template>
    <div class="contract-draft__gray-box contract-draft__gray-box_min-height contract-draft__personal-data">
        <user-image></user-image>
        <user-password></user-password>

        <template  v-if="user.role === 'contractor'">
            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label">
                    Facebook
                </label>
                <input type="text"
                       class="contract-draft__input"
                       placeholder="Facebook"
                       v-model="setting.facebook_url"
                >
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label">
                    Twitter
                </label>
                <input type="text"
                       class="contract-draft__input"
                       placeholder="Twitter"
                       v-model="setting.twitter_url"

                >
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label">
                    Youtube
                </label>
                <input type="text"
                       class="contract-draft__input"
                       placeholder="Youtube"
                       v-model="setting.youtube_url"

                >
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label">
                    LinkedIn
                </label>
                <input type="text"
                       class="contract-draft__input"
                       placeholder="LinkedIn"
                       v-model="setting.linkedin_url"
                >
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label">
                    Pinterest
                </label>
                <input type="text"
                       class="contract-draft__input"
                       placeholder="Pinterest"
                       v-model="setting.pinterest_url"
                >
            </div>

            <div class="contract-draft__input-box">
                <label class="contract-draft__input-label">
                    BBB
                </label>
                <input type="text"
                       class="contract-draft__input"
                       placeholder="BBB"
                       v-model="setting.bbb_url"
                >
            </div>
        </template>

    </div>
</template>

<script>
    import UserImage from './UserImage'
    import UserPassword from './UserPassword'
    import {mapGetters} from 'vuex'

    export default {
        components: {
            'user-image': UserImage,
            'user-password': UserPassword
        },

        props: {
            setting: Object
        },


        computed: {
            ...mapGetters(["user"]),
        },
    }
</script>

<style scoped>

</style>