<template>
    <div class="summary-block">
        <h3 class="summary-block__border-title sum-tour-3">Project Timeline</h3>
        <ul class="summary-timeline-box">

            <li :key="message.id"  v-for="message in timeline" class="summary-time-el">

                <notification :message="message" />

            </li>

        </ul>
    </div>
</template>

<script>
    import Notification from './Notification'
    export default {
        components:{
            'notification': Notification,
        },
        props: [
            'timeline'
        ],
    }
</script>