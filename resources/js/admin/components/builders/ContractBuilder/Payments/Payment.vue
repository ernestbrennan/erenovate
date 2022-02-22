<template>
    <div>
        <div class="milestone-table__row" v-show="mobileOnly">
            <div class="milestone-table__work milestone-table__row-el">
                <input placeholder=""
                       type="text"
                       disabled="disabled"
                       class="contract-draft__input"
                       v-model="workTitle">
            </div>
            <div class="milestone-table__est-price milestone-table__row-el">
                <input placeholder=""
                       type="text"
                       disabled="disabled"
                       class="contract-draft__input"
                       v-model="payment.work.estimated">

            </div>
            <div class="milestone-table__fin-price milestone-table__row-el">
                <input placeholder=""
                       type="text"
                       disabled="disabled"
                       class="contract-draft__input"
                       v-model="payment.work.final">
            </div>
        </div>

        <div class="material-collapse" v-show="!mobileOnly">
            <div ref="workHeader"
                 class="material-collapse__header">
                <div class="material-collapse__title">{{workTitle}}</div>
                <div class="material-collapse__curret">
                    <img src="/img/icon/caret-icon_gray.svg">
                </div>
            </div>
            <div class="material-collapse__body">
                <label class="contract-draft__input-label">Estimated Price</label>
                <input placeholder=""
                       type="text"
                       disabled="disabled"
                       class="contract-draft__input"
                       v-model="payment.work.estimated">
                <label class="contract-draft__input-label">Final Price</label>
                <input placeholder=""
                       type="text"
                       disabled="disabled"
                       class="contract-draft__input"
                       v-model="payment.work.final">
            </div>
        </div>
        <div class="milestone-table__row" v-show="mobileOnly">
            <div class="milestone-table__work milestone-table__row-el">
                <input placeholder=""
                       type="text"
                       disabled="disabled"
                       class="contract-draft__input"
                       v-model="materialTitle">
            </div>
            <div class="milestone-table__est-price milestone-table__row-el">
                <input placeholder=""
                       type="text"
                       disabled="disabled"
                       class="contract-draft__input"
                       v-model="payment.material.estimated">

            </div>
            <div class="milestone-table__fin-price milestone-table__row-el">
                <input placeholder=""
                       type="text"
                       disabled="disabled"
                       class="contract-draft__input"
                       v-model="payment.material.final">
            </div>
        </div>

        <div class="material-collapse" v-show="!mobileOnly">
            <div ref="materialHeader"
                 class="material-collapse__header">
                <div class="material-collapse__title">{{materialTitle}}</div>
                <div class="material-collapse__curret">
                    <img src="/img/icon/caret-icon_gray.svg">
                </div>
            </div>
            <div class="material-collapse__body">
                <label class="contract-draft__input-label">Estimated Price</label>
                <input placeholder=""
                       type="text"
                       disabled="disabled"
                       class="contract-draft__input"
                       v-model="payment.material.estimated">
                <label class="contract-draft__input-label">Final Price</label>
                <input placeholder=""
                       type="text"
                       disabled="disabled"
                       class="contract-draft__input"
                       v-model="payment.material.final">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        
        props: {
            payment: Object,
        },
        computed:{
            mobileOnly(){
                return window.innerWidth >= 768
            },
            workTitle(){
                return `Milestone ${this.payment.milestone_sequence} work`
            },
            materialTitle(){
                return `Milestone ${this.payment.milestone_sequence} materials`
            },
        },
        mounted() {
            $(this.$refs.workHeader).click(function () {
                $(this).toggleClass('active')
                $(this).parent().find('.material-collapse__body').slideToggle()
            })
            $(this.$refs.materialHeader).click(function () {
                $(this).toggleClass('active')
                $(this).parent().find('.material-collapse__body').slideToggle()
            })
        },
    }
</script>

<style scoped>

</style>