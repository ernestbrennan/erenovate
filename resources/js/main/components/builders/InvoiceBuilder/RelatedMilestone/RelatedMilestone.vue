<template>
    <div>
        <div class="contract-draft__border-title">Attachments related to this Milestone</div>

        <div class="contract-draft__input-box">
            <label class="contract-draft__input-label">Milestone Title</label>
            <input type="text"
                   class="contract-draft__input"
                   disabled
                   v-model="milestone.milestone_content.title">
        </div>
        <div class="contract-draft__input-box">
            <label class="contract-draft__input-label">Milestone Description</label>
            <textarea class="contract-draft__textarea"
                      name="contract description"
                      disabled
                      ref="textareaDesc"
                      v-model="milestone.milestone_content.description">
            </textarea>
        </div>
        <template v-if="typeof milestone.milestone_content.batches !=='undefined'">
            <div class="contract-draft__input-box">
                <template v-if="hasMalistoneAttchTitle">
                    <label class="contract-draft__input-label" >Milestone Attachments</label>
                </template>
                <file-attachments :batches="milestone.milestone_content.batches" :readOnly="true" />
            </div>
        </template>
    </div>
</template>

<script>
    import FileAttachments from '../../../common/FileAttachments.vue'
    import {resizeTextareaReadOnly} from '../../../common/Mixins/textarea'

    export default {
        mixins:[resizeTextareaReadOnly],
        components: {
            'file-attachments': FileAttachments
        },
        data() {
            return {}
        },
        props: {
            milestone: Object,
            readOnly: Boolean,
        },
        computed:{
            hasMalistoneAttchTitle(){
                return this.milestone.milestone_content.batches.length
            },
        },
        mounted() {
            this.$refs.textareaDesc.setAttribute('style', 'height:auto');
        },
        beforeUpdate() {
            this.textareaDisabledResize(this.milestone.milestone_content.description)
        },

    }
</script>