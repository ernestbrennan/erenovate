<template>
  <v-app class="general-main">
    <div
      class="global-loader"
      :class="{active: getLoader}"
      v-show="loader"
    >
      <div class="sk-folding-cube">
        <div class="sk-cube sk-cube-1"></div>
        <div class="sk-cube sk-cube-2"></div>
        <div class="sk-cube sk-cube-3"></div>
        <div class="sk-cube sk-cube-4"></div>
      </div>
    </div>
    <app-header v-if="fullWindow"></app-header>

    <v-content>
      <main-dialog v-if="renderDialog" :type="dialogType"></main-dialog>

      <main-alert></main-alert>
      <div
        :style="{height: typeof windowHeight === 'number' ? windowHeight + 'px': windowHeight }"
        :class="{'custom-container_fullreset' : !fullWindow}"
        class="custom-container"
      >
        <router-view :key="$route.fullPath"/>
      </div>
    </v-content>

    <app-sidebar v-if="fullWindow"/>

  </v-app>
</template>

<script>
  import Header from './Header.vue'
  import Sidebar from './Sidebar.vue'
  import MainAlert from './Alert/MainAlert.vue'
  import MainDialog from './Dialogs/MainDialog.vue'

  import {mapGetters} from 'vuex'

  import firebase from 'firebase/app';
  import 'firebase/messaging';
  import {saveFcmToken} from "@/api/user";

  export default {
    components: {
      'app-header': Header,
      'app-sidebar': Sidebar,
      'main-alert': MainAlert,
      'main-dialog': MainDialog,
    },
    data() {
      return {
        fcm_messaging: null,
      }
    },
    computed: {
      ...mapGetters(['user']),

      fullWindow() {
        return !['sign-in', 'sign-up'].includes(this.$route.name);
      },
      renderDialog: {
        get() {
          return this.$store.state.renderDialog
        },
        set(val) {
          return this.$store.commit('set', {type: 'renderDialog', data: val})
        }
      },
      dialogType: {
        get() {
          return this.$store.state.dialogMain.type
        },
        set(val) {
          return this.$store.commit('set', {type: 'dialogMain', data: {type: val}})
        }
      },
      loader: {
        get() {
          return this.$store.state.global_loader
        },
        set(val) {
          this.$store.commit('set', {type: 'global_loader', data: val})
        }
      },
      windowHeight: {
        get() {
          return this.$store.state.containerHeight
        },
        set(value) {
          return this.$store.commit('setContHeight', value)
        },
      },
    },
    created() {
      this.checkIsFirstEnter();
      this.containerHeight();

      if ('Notification' in window && 'serviceWorker' in navigator) {
        this.initFcm();
      }
    },
    mounted() {
      this.containerHeight();
      window.addEventListener('resize', this.containerHeight);
    },
    methods: {
      checkIsFirstEnter() {
        const cookie = this.$cookies.isKey('is_first_object');
        if (cookie === false) {
          const start_state = JSON.stringify(this.$store.state.first_enter_tour)
          this.$cookies.set('is_first_object', start_state, '24d')
          //this.$store.commit('set',{type:'isFirstTime', data: true});
        } else {
          const read_state = this.$cookies.get('is_first_object')
          this.$store.state.first_enter_tour = read_state
        }
      },
      getLoader() {
        setTimeout(() => {
          return this.loader
        }, 10)
      },
      containerHeight() {
        if (this.$route.name === 'sign-in' || this.$route.name === 'sign-up') {
          this.windowHeight = '100%';
          return false;
        }
        let width = window.innerWidth
        let height = window.innerHeight
        $('body').removeAttr("style");
        if (width >= 992) {
          let cont = height - 140
          //this.windowHeight = height - 140
          this.$store.commit('setContHeight', cont)
        } else if (width < 992 && width >= 768) {
          let cont = height - 80
          //this.windowHeight = height - 80
          this.$store.commit('setContHeight', cont)
        } else {
          $('body').css('height', height);
          let cont = height - 60
          //this.windowHeight = height - 60
          this.$store.commit('setContHeight', cont)
        }
      },
      initFcm() {
        firebase.initializeApp({
          apiKey: "AIzaSyAHe9oLWZ5WqV2wILcS1Mz5Ni8CjbzwuPQ",
          authDomain: "erenovate-guarantee.firebaseapp.com",
          databaseURL: "https://erenovate-guarantee.firebaseio.com",
          projectId: "erenovate-guarantee",
          storageBucket: "erenovate-guarantee.appspot.com",
          messagingSenderId: "377026876536",
          appId: "1:377026876536:web:48a8fdaf09b039f30c498d",
          measurementId: "G-TRG0FX140Z"
        });

        this.fcm_messaging = firebase.messaging();
        this.fcm_messaging.usePublicVapidKey("BONr4PhnzME7XpEBcdOG4tFvr7JJlB9TZ3LnmL7GMpv0-eC4m_yGYaZk6mmMx51noMQRO8lKG69DATRKtnWCMfQ");

        if (this.user) {
          this.getFcmToken();
        }

        this.fcm_messaging.onMessage(function (payload) {
          navigator.serviceWorker.register('/firebase-messaging-sw.js');

          Notification.requestPermission(function (permission) {
            if (permission === 'granted') {
              navigator.serviceWorker.ready.then(function (registration) {
                payload.data.data = JSON.parse(JSON.stringify(payload.data));

                registration.showNotification(payload.notification.title, payload.data);

              }).catch(function (error) {
                console.log('ServiceWorker registration failed');
              });
            }
          });
        });
      },
      deleteFcmToken() {
        if (!this.fcm_messaging) return;

        return this.fcm_messaging.getToken()
          .then((currentToken) => {
            if (currentToken) {
              this.fcm_messaging.deleteToken(currentToken)
                .then(function () {
                })
                .catch(function (error) {
                });
            }
          }).catch(function (error) {
          });


      },
      getFcmToken() {
        if (!this.fcm_messaging) return;

        this.fcm_messaging.requestPermission()
          .then(() => {
            this.fcm_messaging.getToken()
              .then(function (currentToken) {
                saveFcmToken(currentToken)
              })
              .catch(function (error) {
                console.log('An error occurred while retrieving token');
              });
          })
          .catch(function (error) {
            console.log('Unable to get permission to notify');
          });
      }
    },
    watch: {
      user: function (val) {
        if (!val.userId) {
          return this.deleteFcmToken();
        }
        return this.getFcmToken();
      }
    },
    beforeUpdate() {
      this.containerHeight();
    },
    updated() {
      this.containerHeight();
    },
  }
</script>
