<template>
  <v-dialog light max-width="556" v-model="show" content-class="main-dialog__scroll-content scrollbar">
    <div class="main-dialog main-dialog_open">

      <div class="main-dialog__header">
        <img @click="close" class="close-dialog" src="/img/icon/close-modal_gray.svg">
        <h5 class="main-dialog__header-title">
          Submit a Price Discrepancy
        </h5>
      </div>

      <div class="main-dialog__body">
        <p class="main-dialog__p">
          Please report a Project Price discrepancy if the final Project Price is inaccurate.
        </p>
        <p class="main-dialog__p">
          We recommend you notify eRenovate if a Final Project Price discrepancy exists,
          since the eRenovate Guarantee directly relates to final project details and cost.
        </p>

        <div class="main-dialog__form-group">
          <label class="main-dialog__label">Report Price Discrepancy (required)</label>
          <textarea
            placeholder="Provide Final Project Price with supporting comments (required)"
                    class="main-dialog__textarea"
                    ref="textareaDesc"
                    v-model="comment"
                    @focus="clearInput('textareaDesc')"
                    @input="checkRequiredComment('comment','textareaDesc')"
          >
          </textarea>
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
            Report
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
        const comment = this.checkRequiredComment('comment', 'textareaDesc');

        if (comment) {
          return this.createReport()
        }

        return false
      },
      createReport() {
        let params = {
          guarantee_project_id: this.$route.params.guarantee_project_id,
          description: this.comment,
          type: 'price_modification'
        }

        api.post('issue.create', params)
          .then(response => {

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
