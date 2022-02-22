<template>
  <div class="settings__img-user-wrapper">
    <div class="settings__img-user-box">
      <img :src="user.photo" :alt="user.name" class="settings__img-user">
    </div>
    <!--<label for="user_img_file" class="main-btn main-btn_border-blue">-->
    <!--<span>Upload image</span>-->
    <!--<input id="user_img_file" @change="inputImg" ref="user_img" type="file" class="file" hidden>-->
    <!--</label>-->
    <hr class="settings__img-user-hr">
  </div>
</template>

<script>
  import {mapGetters} from 'vuex'

  export default {
    name: "UserImage",
    computed: {
      ...mapGetters(["user"]),
    },
  }
</script>
