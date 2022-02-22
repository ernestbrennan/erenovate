<template>
  <div class="welcome-container scrollbar">
    <div class="welcome-comp__wr" :style="{'min-height': this.$store.state.containerHeight + 'px'}">
      <div class="welcome-comp__inner">
        <img
          style="max-width: 100px;"
          src="/img/no-logo-preview.png"
          alt="welcome"
          class="img-fluid welcome-comp__gr-img"
        >
        <template v-if="user.role === 'homeowner'">
          <h3 class="welcome-comp__main-title">
            Welcome to Project Tracker.<br>Homebase for your Projects.
          </h3>
          <p class="welcome-comp__main-text">
            Project Tracker is your easy-to-use platform to stay in sync with Pros and help projects run smoother.
            Project Tracker is free to help organize your project details in one convenient place;
            It’s like your Virtual Project Corkboard to store related documents, images and more.
            Project Tracker powers the eRenovate Guarantee,
            so ask your Pro to make sure you’re protected by the eRenovate Guarantee.
          </p>
        </template>
        <template v-else>
          <h3 class="welcome-comp__main-title">Welcome to Project Tracker. <br> Your Tool to close more leads.</h3>
          <p class="welcome-comp__main-text">
            Project Tracker is your easy tool to stay in sync with clients and help projects run smoother.
            Our free Project Tracker Tool provides a platform to organize your project details in one convenient place.
            The Project Tracker platform <b>powers the eRenovate Guarantee</b>.
            Projects must be registered on Project Tracker to be protected by the eRenovate Guarantee.
            Make sure you inform your client use the simple Project Tracker tool to be covered.
          </p>
        </template>
        <p v-if="subtext" class="welcome-comp__main-text">Communication in English is required.</p>
        <div
          :class="{'welcome-comp__btn-row_center': user.role !== 'homeowner'}"
          class="g-flex g-flex_row welcome-comp__btn-row"
        >
          <v-btn
            v-if="user.role === 'homeowner'"
            depressed
            class="welcome-comp__cencel-btn"
            @click="cancel"
          >
            CANCEL
          </v-btn>
          <v-btn depressed color="#2CCCD3" class="welcome-comp__start-btn" @click="start">PROCEED</v-btn>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import {mapGetters} from 'vuex'
  import {visitProject} from '@/api/project'

  export default {
    data() {
      return {
        subtext: true
      }
    },
    computed: {
      ...mapGetters(["user"]),
    },
    created() {
      this.checkHeight();
    },
    methods: {
      checkHeight() {
        if (window.innerWidth < 576) {
          this.subtext = false
        }
        return true
      },
      start() {
        visitProject(this.$route.params.guarantee_project_id).then(response => {
          localStorage.setItem('is_visited_' + this.$route.params.project_id + '_' + this.user.role, true);
          this.$router.toProjectMessenger();
        });
      },
      cancel() {
        window.location.href = 'http://dev.erenovate.com/homeowners/login/projects'
      }
    }
  }
</script>
