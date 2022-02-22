<template>
    <div class="contract-comp settings" v-if="setting">

        <controls />

        <div class="contract-comp__body component-body_scroll scrollbar settings__body" :style="{height: componetConteinerHeight}" >
            <div class="contract-draft__el-box">
                <div class="contract-draft__slide-main-box">
                    <div class="row">

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <user-info :setting="setting"/>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <user-representation :setting="setting"/>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Controls from "./Controls"
    import UserInfo from './UserInfo/UserInfo'
    import UserRepresentation from './UserRepresentation/UserRepresentation'

    import {ContainerHeight} from '../../common/Mixins/builder'
    import parent_validator from "../../mixins/validator/base/parent_validator";

    import {mapGetters} from 'vuex'

    export default {
        mixins: [ContainerHeight, parent_validator],
        components: {
            'controls': Controls,
            'user-info': UserInfo,
            'user-representation': UserRepresentation,
        },
        created() {
            this.$store.state.global_loader = true;
            this.$store.dispatch('getSettings').then(response => {
                this.$store.state.global_loader = false;
            });
        },
        computed: {
            ...mapGetters(["setting", "user"]),
        },
    }
</script>
