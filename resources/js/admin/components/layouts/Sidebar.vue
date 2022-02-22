<template>
    <div>
        <v-navigation-drawer floating
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
                    <div class="sidebar-user__user-name">{{ user.firstname}}</div>
                </div>
                <div class="sidebarList">
                    <ul class="sidebarList__list-main">
                        <a @clicl.prevent>
                            <li class="sidebar__el-menu sidebar-el_project">
                                <div class="sidebar__list-content" @click="toProjectList">
                                    <img src="/img/icon/contracts_white.svg" alt="icon-menu">
                                    <div class="sidebar__el-title">Projects</div>
                                </div>
                                <!--ul class="sidebarList__list-sub" v-if="guarantee_project_id !== undefined ">
                                    <li class="sidebar__el-project-menu" @click="toCurrentProject">
                                        <img src="/img/icon/contract_white.svg" alt="icon-menu">
                                        <div class="sidebar__el-title">Projects#{{guarantee_project_id}}</div>
                                    </li>
                                </ul-->
                            </li>
                        </a>
                    </ul>
                </div>
                <v-spacer></v-spacer>
            </v-layout>
        </v-navigation-drawer>
    </div>

</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        data() {
            return {
                drawerWidth: 200,
                guarantee_project_id: this.$route.params.guarantee_project_id,
            }
        },
        computed: {
            ...mapGetters(['user']),
            drawer: {
                get() {
                    return this.$store.state.drawer
                },
                set(val) {
                    this.$store.commit('drawer', val)
                }
            }
        },
        methods: {
            toCurrentProject() {
                return this.$router.push({
                    name: 'project-issues',
                    params: {
                        guarantee_project_id: this.guarantee_project_id,
                    }
                })
            },
            toProjectList() {
                return this.$router.push({name: 'project-list'})
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
