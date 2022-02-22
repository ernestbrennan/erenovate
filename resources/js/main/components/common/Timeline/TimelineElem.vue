<template>
        <router-link class="contract-timeline__elements"
                     tag="li"
                     :to="to"
                     :class="getClass(elem.status, extrClass)"
        >
            <div class="contract-timeline__dot"></div>
            <div class="contract-timeline__info">
                <slot></slot>
                <h4 class="contract-timeline__title">{{elem.title}}</h4>
                <h5 class="contract-timeline__status-date">{{statusText(elem.status)}}
                    {{elem.created_at}}</h5>
            </div>
        </router-link>
</template>

<script>
    export default {
        data() {
            return {
                windowHeight: false,
                types: {
                    signed: 'signed',
                    completed: 'completed',
                    in_progress: 'in_progress',
                    pending: 'pending',
                },
            }
        },
        props: [
            'elem',
            'to',
            'extrClass'
        ],
        computed:{

        },
        methods: {
            getClass(status, classes){
                if(classes){
                    return status + ' ' + classes
                } else {
                    return status
                }
            },
            containerHeigth() {
                let height = this.$store.state.containerHeight;
                this.windowHeight = height + 'px'
            },
            statusText(status) {
                if (this.elem.title === 'Invoice') {
                    if (status === 'pending') return 'Waiting';
                    if (status === 'rejected') return 'Declined';
                }

                if (status === 'signed') return 'Signed';
                if (status === 'completed') return 'Completed';
                if (status === 'in_progress') return 'In Progress';
                if (status === 'pending') return 'Pending';
            },
            getTourId(elem) {
            },
        },
        created() {
            this.containerHeigth();
            window.addEventListener('resize', this.containerHeigth)
        },
    }
</script>