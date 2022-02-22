<template>
  <div>
    <v-navigation-drawer
      floating
      app
      fixed
      clipped
      :width="drawerWidth"
      v-model="drawer"
      class="main-sidebar"
    >
      <v-layout column fill-height>
        <div class="sidebar-user hidden-md-and-up">
          <img :src="user.photo" class="sidebar-user__user-img" alt="header_user">
          <div class="sidebar-user__user-name">{{ user.firstname}} {{user.lastname}}</div>
        </div>
        <div class="sidebarList">
          <ul class="sidebarList__list-main">
            <a target="_blank" :href="goToErenovate('dashboard')">
              <li class="sidebar__el-menu sidebar-el_default">
                <div class="sidebar__list-content">
                  <img src="/img/icon/projects_gray.svg" alt="icon-menu">
                  <div class="sidebar__el-title">Dashboard</div>
                </div>
              </li>
            </a>
            <a v-if="user.role === 'homeowner'" target="_blank" :href="goToErenovate('favourites')">
              <li class="sidebar__el-menu sidebar-el_default">
                <div class="sidebar__list-content">
                  <img src="/img/icon/favourites_gray.svg" alt="icon-menu">
                  <div class="sidebar__el-title">Favourites</div>
                </div>
              </li>
            </a>
            <li
              :class="{'active': $route.name !== 'settings' && $route.name !== 'archived-drafts-list' && $route.name !== 'invite-ho' }"
              class="sidebar__el-menu sidebar-el_default">
              <router-link class="sidebar__list-content" to="/projects">
                <i class="material-icons">library_books</i>
                <div class="sidebar__el-title">My projects</div>
              </router-link>
              <ul class="sidebarList__list-sub" v-if="guarantee_project_id !== undefined ">
                <li class="sidebar__el-project-menu" @click="toCurrentProject">
                  <img src="/img/icon/contract_white.svg" alt="icon-menu">
                  <div class="sidebar__el-title">{{project_name}}</div>
                </li>
                <li class="sidebar__el-project-menu sidebar__el-project-menu_issue"
                    v-if="hasPriceDiscrepancy"
                    @click="toIssue"
                >
                  <img src="/img/icon/bell_white.svg" alt="icon-menu">
                  <div class="sidebar__el-title">Price <br>discrepancy</div>
                </li>
              </ul>
            </li>
            <router-link v-if="user.role === 'contractor'" to="/archived-drafts">
              <li class="sidebar__el-menu sidebar-el_default"
                  :class="{'active' : $route.name === 'archived-drafts-list'}">

                <div class="sidebar__list-content">
                  <i class="material-icons">edit</i>
                  <!--<img src="/img/icon/archived_draft.svg" alt="icon-menu" width="25">-->
                  <div class="sidebar__el-title">Drafts</div>
                </div>
              </li>
            </router-link>
            <a target="_blank" v-if="user.role === 'contractor'" :href="goToErenovate('profile')">
              <li class="sidebar__el-menu sidebar-el_default">
                <div class="sidebar__list-content">
                  <img src="/img/icon/profile_gray.svg" alt="icon-menu">
                  <div class="sidebar__el-title">Biz Profile</div>
                </div>
              </li>
            </a>
            <router-link to="/settings">
              <li  class="sidebar__el-menu sidebar-el_default"
                  :class="{'active': $route.name === 'settings'}">
                <div class="sidebar__list-content">
                  <i class="material-icons">settings_applications</i>
                  <div class="sidebar__el-title">
                    <template v-if="user.role === 'homeowner'">Profile</template>
                    <template v-else>Settings</template>
                  </div>
                </div>
              </li>
            </router-link>
          </ul>
        </div>
        <v-spacer></v-spacer>
        <div class="sidebar__bottom-links hidden-md-and-up">
          <v-btn
            depressed
            color="#1875F0"
            dark
            @click="logout"
            class="sidebar__post-my-project">
            LOG OUT
            <img style="margin-left: 5px;" src="/img/icon/logout_icon.svg" alt="logout">
          </v-btn>
          <v-btn
            depressed
            color="#1875F0"
            dark
            @click="toInvite"
            class="sidebar__post-my-project">
            <template v-if="user.role === 'homeowner'">
              REQUEST AN INVITE
            </template>
            <template v-else>
              <!--INVITE A CLIENT-->
              START A PROJECT
            </template>
          </v-btn>
        </div>
      </v-layout>
    </v-navigation-drawer>
  </div>

</template>

<script>
  //messages: 'https://www.erenovate.com/homeowners/messages',
  //messages: 'https://erenovate.com/contractors/messages',
  import {mapGetters} from 'vuex'

  export default {
    data() {
      return {
        drawerWidth: 200,
        bottomList: [{
          title: 'Settings',
          route: '/settings',
          icon: '/img/icon/settings_gray.svg'
        },
          {
            title: 'Logout',
            route: '/logout',
            icon: '/img/icon/logout_gray.svg'
          }
        ],
        guarantee_project_id: this.$route.params.guarantee_project_id,
        holink: {
          dashboard: 'https://www.erenovate.com/homeowners/login/projects',

          favourites: 'https://www.erenovate.com/homeowners/favourites-idea-gallery',
          profile: 'http://pro.dev.erenovate.com/settings',
        },
        prolink: {
          dashboard: 'https://erenovate.com/contractors/leads',

          profile: '',
        },
      }
    },
    computed: {
      ...mapGetters(['user', 'has_current_invoice', 'guarantee_project', 'contracts']),
      drawer: {
        get() {
          return this.$store.state.drawer
        },
        set(val) {
          this.$store.commit('drawer', val)
        }
      },
      project_name() {
        return this.guarantee_project.project_name || '#' + this.guarantee_project_id
      },
      hasPriceDiscrepancy() {
        return this.guarantee_project.price_discrepancy
      }

    },
    methods: {
      logout() {
        api.post('user.logout').then(response => {

          this.$store.state.user = {};

          this.$router.push({
            name: 'sign-in'
          })
        })
      },
      toInvite() {
        if (this.user.role === 'homeowner') {
          window.open('http://dev.erenovate.com/homeowners/postjob', "_blank")
        } else {
          this.$router.push({
            name: 'invite-ho',
          })
        }

      },
      goToSettings() {
        return this.$router.push({
          name: 'settings',
        })
      },
      toCurrentProject() {
        let targetId = this.$route.params.guarantee_project_id;

        if (this.guarantee_project.contract_status === 'finished' || this.guarantee_project.contract_status === 'completed') {
          return this.$router.push({
            name: 'summary',
            params: {guarantee_project_id: targetId}
          })
        } else if (this.guarantee_project.contract_status === 'signed') {
          return this.$router.push({
            name: 'current-milestone',
            params: {guarantee_project_id: targetId}
          })
        } else {
          return this.$router.push({
            name: 'messages',
            params: {guarantee_project_id: targetId}
          });
        }
      },
      toProjectList() {
        return this.$router.push({name: 'projects-list'})
      },
      toArchivedDraftList() {
        return this.$router.push({name: 'archived-drafts-list'})
      },
      toIssue() {
        this.$router.push({
          name: 'issue',
          params: {
            guarantee_project_id: this.$route.params.guarantee_project_id,
            issue_id: this.guarantee_project.price_discrepancy.id,
          }
        })
        return false;
      },
      goToErenovate(route) {
        if (this.user.role === 'homeowner') {
          return this.holink[route]
        } else {
          if (route === 'profile') {
            return this.user.profile_link
          }
          return this.prolink[route]
        }

      },
      windowDrawer() {
        if (window.innerWidth < 1264) {
          this.drawer = false
          this.drawerWidth = 250
        }
      },
      setDrawerResize() {
        this.drawerWidth = (window.innerWidth < 1264) ? 250 : 200;
        return true
      },
    },
    created() {
      this.windowDrawer()
      window.addEventListener('resize', this.setDrawerResize)
    },
    watch: {
      $route() {
        this.guarantee_project_id = this.$route.params.guarantee_project_id;
      }
    }
  }
</script>

<style>
  .sidebar__el-menu {
    cursor: pointer;
  }

  .sidebar__el-menu.active, .sidebar__el-menu.active:hover {
    background-color: rgba(48, 131, 242, 0.8) !important;

    color: #fff !important;
    font-family: Roboto;
    font-style: normal;
    font-weight: 500;
    line-height: 24px;
    font-size: 16px;
  }

  .sidebar__el-menu.active .sidebar__list-content {
    background-color: rgba(48, 131, 242, 0.8) !important;
  }

  .sidebar__el-menu.active .sidebar__el-title {
    color: #fff !important;
    font-weight: 500 !important;
  }

  .sidebar__el-menu.sidebar-el_default i {
    color: rgba(127, 127, 127, .5);
  }

  .sidebar__el-menu.active i {
    color: #fff;
  }
</style>
