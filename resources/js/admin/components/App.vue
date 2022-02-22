<template>
    <v-app class="general-main">
        <div v-show="loader" class="global-loader"
             :class="{active: getLoader}"
        >
            <div class="sk-folding-cube">
                <div class="sk-cube sk-cube-1"></div>
                <div class="sk-cube sk-cube-2"></div>
                <div class="sk-cube sk-cube-3"></div>
                <div class="sk-cube sk-cube-4"></div>
            </div>
        </div>
        <app-header v-if="$route.name !== 'login'"></app-header>

        <v-content>
            <main-alert></main-alert>
            <div :style="{height: windowHeight}" class="custom-container">
                <router-view  :key="$route.fullPath"></router-view>
            </div>
        </v-content>

        <app-sidebar v-if="$route.name !== 'login'"></app-sidebar>

    </v-app>
</template>

<script>
    import Header from './layouts/Header.vue'
    import Sidebar from './layouts/Sidebar.vue'
    import MainAlert from './layouts/Alert/MainAlert.vue'

    export default {
        components: {
            'app-header': Header,
            'app-sidebar': Sidebar,
            'main-alert': MainAlert,
        },
        data() {
            return {
                windowHeight: false,
            }
        },
        computed: {
            loader: {
                get() {
                    return this.$store.state.global_loader
                },
                set(val) {
                    this.$store.commit('set', {type: 'global_loader', data: val})
                }
            }
        },
        methods:{
            getLoader(){
                setTimeout(() => {return this.loader},10)
            },
            containerHeight() {
                let width = window.innerWidth
                let height = window.innerHeight
                if (width >= 992) {
                    let cont = height - 140
                    this.windowHeight = height - 140 +'px'
                    this.$store.commit('setContHeight', cont)
                } else if (width < 992 && width >= 768){
                    let cont = height - 80
                    this.windowHeight = height - 80 +'px'
                    this.$store.commit('setContHeight', cont)
                } else {
                    let cont = height - 60
                    this.windowHeight = height - 60 +'px'
                    this.$store.commit('setContHeight', cont)
                }
            }
        },
        created(){

            this.containerHeight();
            window.addEventListener('resize', this.containerHeight);
        }
    }
</script>
<style scoped>
</style>
