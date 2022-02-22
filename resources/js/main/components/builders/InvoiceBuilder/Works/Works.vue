<template>
    <div>
        <h3 class="contract__gray-box-title">
            List of Services
            <v-tooltip right>
                <img class="contract-draft__tooltip"
                     slot="activator"
                     :src="'/img/icon/info-icon_gray.svg'">
                <span>Used to provide an overview of required; about service costs relating to the cost of employees or sub-trades</span>
            </v-tooltip>
        </h3>

        <hr class="mb-5">

        <template v-if="onDesktop">
            <div class="list-works__table-titles">
                <div class="list-works-invoice__table-row">
                    <div class="list-works-invoice__description list-works-invoice__table-row-el">
                        <div class="list-works-invoice__d-title">Description</div>
                    </div>
                    <div class="list-works-invoice__quantity list-works-invoice__table-row-el">
                        <div class="list-works-invoice__d-title">Quantity</div>
                    </div>
                    <div class="list-works-invoice__per-units list-works-invoice__table-row-el">
                        <div class="list-works-invoice__d-title">Price per Unit</div>
                    </div>
                    <div class="list-works-invoice__per-total list-works-invoice__table-row-el">
                        <div class="list-works-invoice__d-title">Total Per Item</div>
                    </div>
                    <div v-if="!readOnly" class="list-works-invoice__remove list-works-invoice__table-row-el"></div>
                </div>
            </div>
        </template>
        <div class="list-works-invoice__list">

            <div class="list-works-invoice__elment" v-for="(work, index) in getList" :key="index">
                <work :readOnly="readOnly"
                      :index="index"
                      :work="work"
                      @removeWork="removeWork">
                </work>
            </div>

        </div>
        <div class="add-invoice-work-row">
            <template v-if="!readOnly">
                <button @click="addWork" class="main-btn main-btn_border-blue">ADD WORK ITEM</button>
            </template>
        </div>
    </div>
</template>
<script>
    import Work from './Work'

    export default {
        components: {
            'work': Work
        },
        data() {
            return {
                new_work: {
                    title: '',
                    quantity: 1,
                    price: 1,
                },
            }
        },
        props: [
            'works',
            'readOnly'
        ],
        computed: {
            onDesktop() {
                return window.innerWidth >= 768
            },
            getList() {
                if (!this.works.length) {
                    this.addNewWork()
                }

                return this.works
            },
        },
        methods: {
            addNewWork() {
                this.works.push(JSON.parse(JSON.stringify(this.new_work)));
            },
            removeWork(index) {
                this.works.splice(index, 1);
            },
            addWork() {
                this.works.push(JSON.parse(JSON.stringify(this.new_work)));
            },
        }
    }
</script>
