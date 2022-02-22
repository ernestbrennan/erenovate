<template>
    <div class="row">
        <div data-v-step="ccd-4" class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
            <current-info :current="current" :readOnly="readOnly" :status_signed="status_signed"></current-info>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
            <interlocutor-info :interlocutor="interlocutor" :status_signed="status_signed"></interlocutor-info>
        </div>
    </div>
</template>
<script>
    import CurrentInfo from './CurrentInfo'
    import InterlocutorInfo from './InterlocutorInfo'

    export default {
        components: {
            'current-info': CurrentInfo,
            'interlocutor-info': InterlocutorInfo
        },
        props: [
            'current',
            'interlocutor',
            'readOnly',
            'status_signed'
        ],
    }
</script>

<style>

</style>
