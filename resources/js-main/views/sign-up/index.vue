<template>
  <div class="full-window">
    <div class="full-window__tw-row">
      <div class="full-window__std-col sign-up__form-wrapper">
        <div class="full-window__inner-box sign-up__form-box">
          <div class="sign-up__form-inner">
            <img class="sign-up__mobile-logo" src="/img/ereno_logoshield_horizontal_gray.svg">
            <div class="sign-up__text-left-box">
              <h2 class="sign-up__title">Project Scope by eRenovate</h2>
              <p class="sign-up__header-text">
                Our easy-to-use platform is free and easy to get started.
                Track and manage projects with your Pro like never before.
              </p>
              <p class="sign-up__header-text">
                Already have an account?
                <router-link to="/sign-in" class="sing-up__blue-link">Log in here</router-link>
              </p>
            </div>
            <div class="full-window__form-els">
              <label class="full-window__form-input-label" :class="{'invalid-label': errors.has('name')}">
                First Name
              </label>
              <input
                class="full-window__input"
                type="text"
                name="first_name"
                :class="{'invalid-input' : errors.has('first_name')}"
                v-validate="'required|min:2'"
                data-vv-as="first name"
                v-model="first_name"
                key="first_name"
              >
              <div v-if="errors.has('first_name')" class="invalid-message">{{ errors.first('first_name') }}</div>
            </div>
            <div class="full-window__form-els">
              <label class="full-window__form-input-label" :class="{'invalid-label': errors.has('email')}">
                Primary email adress
              </label>
              <input
                class="full-window__input"
                type="email"
                name="email"
                :class="{'invalid-input' : errors.has('email')}"
                v-validate="'required|email'"
                v-model="email"
                key="email"
              >
              <div v-if="errors.has('email')" class="invalid-message">{{ errors.first('email') }}</div>
            </div>
            <div class="full-window__form-els">
              <label class="full-window__form-input-label">Registration type</label>
              <div class="switch-btns">
                <button
                  class="switch-btns__btn left"
                  :class="{'active': role === 'homeowner'}"
                  @click="role = 'homeowner'"
                >
                  I'M A HOMEOWNER
                </button>
                <button
                  class="switch-btns__btn right"
                  :class="{'active': role === 'contractor'}"
                  @click="role = 'contractor'"
                >
                  I'M A PRO
                </button>
              </div>
            </div>
            <div class="full-window__form-els">
              <label class="full-window__form-input-label" :class="{'invalid-label': errors.has('password')}">
                Password
              </label>
              <input
                class="full-window__input"
                type="password"
                key="password"
                name="password"
                v-validate="'required|min:6'"
                :class="{'invalid-input' : errors.has('password')}"
                v-model="password"
              >
              <div v-if="errors.has('password')" class="invalid-message">{{ errors.first('password') }}</div>
            </div>
            <div class="full-window__form-els">
              <button class="full-window__btn-green" @click="signUp"><span>SIGN UP</span></button>
            </div>
          </div>
        </div>
      </div>
      <div class="full-window__std-col sign-up__bckg-box">
        <div class="full-window__inner-box sign-up__info-box">
          <img src="/img/ereno_logoshield_horizontal_gray.svg" alt="logo" class="sign-up__logo">
          <h3 class="sign-up__right-title">Homebase for Home Projects</h3>
          <ul class="sign-up__list-ul">
            <li class="sign-up__list-li">
              <img src="/img/list-bullet.svg" alt="bullet-icon">
              <p>Project Communication Tool</p>
            </li>
            <li class="sign-up__list-li">
              <img src="/img/list-bullet.svg" alt="bullet-icon">
              <p>Manage Project Deliverables</p>
            </li>
            <li class="sign-up__list-li">
              <img src="/img/list-bullet.svg" alt="bullet-icon">
              <p>Track Project Costs & Edits</p>
            </li>
            <li class="sign-up__list-li">
              <img src="/img/list-bullet.svg" alt="bullet-icon">
              <p>Private Notes Area</p>
            </li>
            <li class="sign-up__list-li">
              <p>All this and much more…</p>
            </li>
          </ul>
          <div class="sign-up__partners-box">
            <div class="sign-up__partners-title">Trusted by Bonded Pros™, homeowners and our group of Partners;</div>
            <div class="sign-up__partners-img-row">
              <img class="sign-up__partners-img" src="/img/partners/1.png" alt="icon-partners">
              <img class="sign-up__partners-img" src="/img/partners/2.png" alt="icon-partners">
              <img class="sign-up__partners-img" src="/img/partners/3.png" alt="icon-partners">
              <img class="sign-up__partners-img" src="/img/partners/4.png" alt="icon-partners">
              <img class="sign-up__partners-img" src="/img/partners/5.png" alt="icon-partners">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import {signUp} from '@/api/auth'

  export default {
    name: "SignIn",
    data() {
      return {
        email: '',
        password: '',
        first_name: '',
        role: 'homeowner'
      }
    },
    methods: {
      signUp() {
        this.$validator.reset().then(result => {
          signUp(this.email, this.password, this.first_name, this.role)
            .then(request => {

              this.$store.state.user = request.data.response;

              return this.$router.toProjects();
            })
            .catch(error => {

              for (let error_filed in error.message) {
                this.errors.add({
                  field: error_filed,
                  msg: error.message[error_filed].join('\r\n')
                })
              }
            })
        })
      },
    },
  }
</script>
