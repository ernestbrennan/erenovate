<template>
  <v-dialog light max-width="556" v-model="show" content-class="main-dialog__scroll-content scrollbar">
    <div class="main-dialog main-dialog_open">

      <div class="main-dialog__header">
        <img @click="close" class="close-dialog" src="/img/icon/close-modal_gray.svg">
        <h5 class="main-dialog__header-title">
          Report Workmanship Issue
        </h5>
      </div>

      <div class="main-dialog__body">
        <p class="main-dialog__p">
          To report a Workmanship related issue, simply describe the issue as best you can, to notify the Pro.
          <strong>Note: You can upload supporting images on next screen.</strong>
        </p>
        <p class="main-dialog__p">
          Almost all issues are resolved directly with the Pro, and both parties are required to do their best
          to resolve them.
        </p>
        <p class="main-dialog__p">
          eRenovate is here to help if required.
        </p>
        <div class="main-dialog__form-group">
          <label class="main-dialog__label">Name the Issue (required)</label>
          <input
            type="text"
            v-model="title"
            ref="title"
            @focus="clearInput('title')"
            placeholder="Name the issue you are reporting to Pro"
            @input="checkRequiredComment('title','title')"
            class="main-dialog__input-text"
          >
          <div class="invalid-message">
            This field required
          </div>
        </div>
        <div class="main-dialog__form-group">
          <label class="main-dialog__label">Describe the issue (required)</label>
          <textarea
            @focus="clearInput('textareaDesc')"
            ref="textareaDesc"
            v-model="comment"
            placeholder="Provide details of the issue here"
            class="main-dialog__textarea"
            @input="checkRequiredComment('comment','textareaDesc')"
          ></textarea>
          <div class="invalid-message">
            This field required
          </div>
        </div>
      </div>

      <div class="main-dialog__footer">
        <div class="main-dialog__footer-btn-row">
          <button class="main-btn main-btn_border-red" @click="close">
            CANCEL
          </button>
          <button class="main-btn main-btn_full-blue" @click="checkInputs">
            REPORT ISSUE
          </button>
        </div>
      </div>
    </div>
  </v-dialog>
</template>

<script>
  import {nextTickResize} from "@/components/common/Mixins/textarea";

  export default {
    name: "IssueDialog",
    mixins: [nextTickResize],
    data() {
      return {
        comment: '',
        title: '',
      }
    },
    props: {
      value: Boolean,
    },
    computed: {
      show: {
        get() {
          return this.value
        },
        set(value) {
          this.$emit('input', value)
        }
      },
    },
    methods: {
      clearInput(ref_id) {
        let ref = this.$refs[ref_id];
        $(ref).removeClass('invalid-input');
      },
      checkRequiredComment(value, ref_id) {
        let state = this[value].length > 0;
        let ref = this.$refs[ref_id];
        if (!state) {
          $(ref).addClass('invalid-input');
        } else {
          $(ref).removeClass('invalid-input');
        }
        return state
      },
      close() {
        this.show = false;
      },
      checkInputs() {
        const title = this.checkRequiredComment('title', 'title'),
          comment = this.checkRequiredComment('comment', 'textareaDesc');
        if (title && comment) {
          this.createReport()
        } else {
          return false
        }
      },
      createReport() {
        this.$store.state.global_loader = true;

        api
          .post('issue.create', {
            guarantee_project_id: this.$route.params.guarantee_project_id,
            title: this.title,
            description: this.comment,
            type: 'issue'
          })
          .then(response => {

            this.$store.state.global_loader = false;

            this.$router.push({
              name: 'issue',
              params: {
                guarantee_project_id: this.$route.params.guarantee_project_id,
                issue_id: response.data.issue.id,
              }
            })
          });
      },
    },
  }
</script>
