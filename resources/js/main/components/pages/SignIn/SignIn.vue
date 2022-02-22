<template>
    <div class="full-window">
        <div class="full-window__tw-row">
            <div class="full-window__std-col sign-in__form-wrapper">
                <div class="full-window__inner-box sign-in__form-box">
                    <img src="/img/ereno_logoshield_horizontal_gray.svg" alt="logo" class="sign-in__logo">
                    <h3 class="sign-in__form-title">
                        Welcome back!
                    </h3>
                    <div class="full-window__form-els">
                        <label class="full-window__form-input-label" :class="{'invalid-label': errors.has('email')}">
                            Primary email adress
                        </label>
                        <input class="full-window__input"
                               type="email"
                               name="email"
                               :class="{'invalid-input' : errors.has('email')}"
                               v-validate="'required|email'" v-model="email"
                               key="email">
                        <div v-if="errors.has('email')" class="invalid-message">
                            {{ errors.first('email') }}
                        </div>
                    </div>
                    <div class="full-window__form-els">
                        <label class="full-window__form-input-label" :class="{'invalid-label': errors.has('password')}">
                            Password
                        </label>
                        <input class="full-window__input"
                               type="password"
                               name="password"
                               :class="{'invalid-input' : errors.has('password')}"
                               v-validate="'required|min:6'"
                               v-model="password"
                               key="password">
                        <div v-if="errors.has('password')" class="invalid-message">
                            {{ errors.first('password') }}
                        </div>
                    </div>

                    <div class="full-window__form-els">
                        <label class="sign-in__link-green">
                            <span @click="toForgotPass">Forgot password?</span>
                        </label>
                        <button class="full-window__btn-green" @click="signIn">
                            <span>SIGN IN</span>
                        </button>
                    </div>
                    <div class="sign-in__advice-box">
                        <p class="sign-in__advice-text">
                            Don’t have an account?
                            <span @click="toSignUp" class="sign-in__advice-link"> Sign Up</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="full-window__std-col sign-in__bckg-box">
                <div class="full-window__inner-box sign-in__info-box">
                    <h2 class="sign-in__main-title">
                        Welcome to eRenovate. <br>Homebase for home projects.
                    </h2>
                    <h3 class="sign-in__main-subtitle">
                        The easiest way to track projects, stay <br> in sync
                        with your Pro and <b>Be Protected!</b><br>
                        <a target="_blank" href="https://erenovate.com/homeowners/referrals">Refer Friends and Earn</a>
                    </h3>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SignIn",
        data() {
            return {
                email: '',
                password: '',
            }
        },
        computed: {},
        methods: {
            toForgotPass() {
                return false
            },
            signIn() {

                this.$validator.validateAll().then((result) => {

                    api.post('user.signIn', {
                        'email': this.email,
                        'password': this.password
                    }).then(request => {
                        this.$store.state.user = request.data.user;
                        return this.$router.push({
                            name: 'projects-list'
                        })
                    }).catch(error => {

                        for (let error_filed in error.response.data.message) {

                            this.errors.add({
                                field: error_filed,
                                msg: error.response.data.message[error_filed].join('\r\n')
                            })
                        }
                    })
                });
            },
            toSignUp() {
                return this.$router.push({
                    name: 'sign-up'
                })
            }
        },
    }
</script>
