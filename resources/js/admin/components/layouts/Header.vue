<template>
    <div>
        <v-toolbar
                app
                fixed
                clipped-left
                color="#fff"
                :height="headerHeightNum"
                class="main-header"
        >
            <v-btn :class="{ active: drawer}" flat icon class="header-burger hidden-lg-and-up" @click="drawerToggle">
                <span></span>
            </v-btn>
            <v-toolbar-title>
                <img class="hidden-sm-and-down" v-bind:src="'/img/logo.svg'" alt="logo">
            </v-toolbar-title>
            <v-spacer class="hidden-sm-and-down"></v-spacer>
            <!--<app-header-slot></app-header-slot>-->
            <!--<app-header-controls></app-header-controls>-->
            <v-toolbar-items class="hidden-sm-and-down">
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
    import {mapGetters} from 'vuex';

    export default {
        components: {
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
            ...mapGetters(["user"]),
        },
        methods: {
            logoutLink(){
                const url = 'https://www.erenovate.com/homeowners/login'
                window.open(url,'_self');
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
            },
            logout(){
                localStorage.removeItem('token');
                window.api.defaults.headers.common['admin-api-token'] = '';

                this.$router.push({
                    name: 'login'
                });
            }
        },
        created() {
            this.headerHeight()
            window.addEventListener('resize', this.headerHeight)
        }
    }
</script>