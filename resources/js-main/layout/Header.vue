<template>
  <div>
    <v-toolbar
      app
      fixed
      clipped-left
      color="#fff"
      :height="headerHeightNum"
      class="main-header "
    >
      <v-btn :class="{ active: drawer}" flat icon class="header-burger hidden-lg-and-up" @click="drawerToggle">
        <span></span>
      </v-btn>
      <v-toolbar-title>
        <img class="hidden-sm-and-down" src="/img/ereno_logoshield_horizontal.svg" alt="logo">
      </v-toolbar-title>
      <v-spacer class="hidden-sm-and-down "></v-spacer>
      <app-header-slot></app-header-slot>
      <app-header-controls></app-header-controls>
      <v-toolbar-items class="hidden-sm-and-down">
        <v-btn v-if="isHasTour" class="ms-2-class-header" flat @click="startTour">Tour</v-btn>
        <v-btn href="https://www.erenovate.com/blog/" flat target="_blank">advice</v-btn>
        <v-btn href="https://www.erenovate.com/homeowners/referrals" flat target="_blank">refer friends</v-btn>
        <v-btn href="https://www.erenovate.com/homeowners/contests" flat target="_blank">contests</v-btn>
        <v-btn
          href="https://www.erenovate.com/homeowners/post-my-project"
          target="_blank"
          depressed
          color="#1875F0"
          class="header__post-my-project">
          Post My Project
        </v-btn>
        <v-menu offset-y>
          <v-btn
            class="header__user-dropdown"
            slot="activator"
            color="primary"
            flat
          >
            <img :src="user.photo" class="header__user-img" alt="header_user">
            <div class="header__user-name">
              {{user.firstname}}
            </div>
            <span class="dropdown-carret">
              <img :src="'/img/dropdown-carret.svg'" alt="">
            </span>
          </v-btn>
          <div class="contract-header__drop-target">
            <ul class="option-list-dropdown">
              <li v-for="el in headerLinks" @click="openLink(el.link)" class="option-list-dropdown__el">
                <span>{{ el.title }}</span>
              </li>
              <li @click="settingsLink" class="option-list-dropdown__el">
                <template v-if="user.role === 'homeowner'">
                  <span>Profile</span>
                </template>
                <template v-else>
                  <span>Settings</span>
                </template>
              </li>
              <li @click="logout" class="option-list-dropdown__el">
                <span>Log Out</span>
              </li>
            </ul>
          </div>
        </v-menu>
      </v-toolbar-items>
    </v-toolbar>
  </div>
</template>

<script>
  import HeaderSlot from './MobileCenterInfo/HeaderSlot.vue';
  import HeaderControls from './MobileControls/HeaderControls.vue';
  import HeaderLinks from './headerLinks'
  import {mapGetters} from 'vuex';
  import config from '@/config'
  import {logout} from "@/api/auth";

  export default {
    mixins: [HeaderLinks],
    components: {
      'app-header-slot': HeaderSlot,
      'app-header-controls': HeaderControls
    },
    data() {
      return {
        headerHeightNum: 60,
      }
    },
    computed: {
      drawer: {
        get() {
          return this.$store.state.drawer
        },
        set(val) {
          this.$store.commit('drawer', val)
        }
      },
      ...mapGetters({
        user: 'user',
        summary_contract: 'summary/contract',
        summary_loaded: 'summary/loaded',
      }),
      headerLinks() {
        return this.user.role === 'homeowner' ? this.lHomeowner : this.lPro
      },
      isHasTour() {
        const name = this.$route.name;
        switch (name) {
          case 'current-milestone':
          case 'projects-list':
          case 'new-draft':
          case 'list-invoice':
          case 'create-invoice':
          case 'current-invoice':
          case 'messages':
          case 'notes':
          case 'fileHistory':
          case 'issue':
            return true;
            break;

          case 'summary':
            if (!this.summary_loaded) {
              return false
            } else {
              const valid = ['pending', 'signed'].includes(this.summary_contract.status);
              if (valid) {
                return false
              }
            }
            return true;
            break;
          default:
            return false;
            break;
        }
      }
    },
    methods: {
      settingsLink() {
        return this.$router.push({
          name: 'settings',
        })
      },
      logoutLink() {
        const url = this.user.role === 'homeowner' ?
          config.homeowner_logout_link :
          config.contractor_logout_link;

        window.open(url, '_self');
      },
      logout() {

        logout().then(response => {
          this.$store.state.user = {};

          this.$router.push({
            name: 'sign-in'
          })
        })
      },

      openLink(url) {
        window.open(url, '_blank');
      },
      startTour() {
        const name = this.$route.name;
        switch (name) {
          case 'current-milestone':
            this.$tours['tourMilestone'].start();
            break;
          case 'projects-list':
            this.$tours['tourContracts'].start();
            break;
          case 'new-draft':
            this.$tours['tourCreateContract'].start();
            break;
          case 'list-invoice':
            this.$tours['tourInvoiceList'].start();
            break;
          case 'create-invoice':
            this.$tours['tourInvoice'].start();
            break;
          case 'current-invoice':
            if (this.user.role === 'homeowner') {
              this.$tours['tourCurrInvoice'].start();
            }
            break;
          case 'messages':
            this.$tours['tourMessenger'].start();
            break;
          case 'notes':
            this.$tours['tourNotes'].start();
            break;
          case 'fileHistory':
            this.$tours['tourFiles'].start();
            break;
          case 'summary':
            this.$tours['tourSummary'].start();
            break;
          case 'issue':
            this.$tours['tourIssue'].start();
            break;
          default:
            break;

        }
      },
      drawerToggle() {
        this.drawer = !this.drawer
        if (this.drawer === true) {
          let timer = setTimeout(() => {
            $('.v-overlay.v-overlay--active').addClass('v-overlay_black');
            clearTimeout(timer)
          }, 50)
        }
      },
      headerHeight() {
        let w = window.innerWidth
        if (w >= 992) {
          this.headerHeightNum = 60
        } else if (w < 992 && w >= 768) {
          this.headerHeightNum = 80
        } else {
          this.headerHeightNum = 60
        }
      }
    },
    created() {
      this.headerHeight()
      window.addEventListener('resize', this.headerHeight)
    }
  }
</script>
