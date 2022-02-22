<template>
    <div class="summary-block">
        <h3 class="summary-block__border-title">Project Completed</h3>
        <div class="summary-block__gray-box">
            <template v-if="user.role === 'contractor'">

                <template v-if="summary.contract.ho_confirm_complete">
                    <div class="summary-block__ta-center">
                        <img src="/img/paypal_blue.svg" alt="" class="summary-block__img-st">
                        <div class="summary-block__title-st sum-tour-ho3s">Pay Additional Project Fee</div>
                        <p class="summary-block__text-st">
                            Project Price has changed since the initial Project Details
                            Scope. You are required to pay the additional Project Guarantee Fee to complete this project
                            and to activate the client's 88 Day Workmanship Guarantee period. Please pay additional fee
                            by clicking the button below…
                        </p>
                        <button @click="dialogPay = true" class="sum-tour-ho4s summary-block__btn-st main-btn main-btn_full-blue">
                            PAY FEE
                        </button>
                        <paypal v-model="dialogPay"></paypal>
                    </div>
                </template>
                <template v-else>
                    <div class="summary-block__ta-center">
                        <img src="/img/clock_blue.svg" alt="" class="summary-block__img-st">
                        <div class="summary-block__title-st sum-tour-ho3s">Project completed, waiting for approval.</div>
                        <p class="summary-block__text-st">
                            You changed status of this project to Completed.<br>
                            Waiting for the homeowner (client) to accept the
                            Project as completed. Once clients confirms Project Completion, the 88 Day Workmanship Guarantee period begins.<br>
                            Have questions? Feel free to <a target="_blank" class="common-link common-link_blue" href="https://www.erenovate.com/homeowners/contact">Contact Us</a>
                        </p>
                    </div>
                </template>
            </template>

            <template v-else>
                <div class="summary-block__ta-center">
                    <img src="/img/clock_blue.svg" alt="" class="summary-block__img-st">
                    <div class="summary-block__title-st">Waiting for eRenovate to review Project Summary.</div>
                    <p class="summary-block__text-st">
                        Your Project Completed status is Pending, while an eRenovate Project Advisor reviews the final Project Summary. Once approved, the 88 Day Workmanship Guarantee for your project will begin.
                    </p>
                </div>
            </template>

        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import Paypal from './Paypal'

    export default {
        components: {
            'paypal': Paypal
        },
        data() {
            return {
                fin: true,
                dialogPay: false,
            }
        },
        computed: {
            ...mapGetters(["user", "summary"])
        },
    }
</script>

