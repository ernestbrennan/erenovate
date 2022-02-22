<template>
    <router-link class="contract-timeline__elements"
                 tag="li"
                 :to="to"
                 :class="elem.status"
    >
        <div class="contract-timeline__dot"></div>
        <div class="contract-timeline__info">
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
            'to'
        ],
        methods: {
            statusText(status) {
                if (status === 'signed') return 'Signed';
                if (status === 'completed') return 'Completed';
                if (status === 'in_progress') return 'In Progress';
                if (status === 'pending') return 'Pending';
            },

        },
    }
</script>
