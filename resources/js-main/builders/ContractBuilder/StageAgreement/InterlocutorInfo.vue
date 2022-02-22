<template>
  <div class="contract-draft__gray-box contract-draft__gray-box_min-height" :log="log">
    <h3 class="contract-draft__box-title">{{user_contract_text}}</h3>
    <template v-if="interlocutor.status === 'new'">
      <div class="contract-draft__empty-contractor-inf">
        <div class="box">
          <img :src="'/img/user-contract_gray.svg'" alt="user">
          <p class="common-p"> {{ new_draft_pending }} </p>
        </div>
      </div>
    </template>
    <template v-else aria-disabled="true">
      <div class="contract-draft__contractor-inputs" data-v-step="ccd-5">
        <template v-if="interlocutor.type === 'individual'">

          <div class="contract-draft__input-box">
            <label class="contract-draft__input-label">First Name</label>
            <input
              type="text"
              class="contract-draft__input"
              placeholder="Example: John"
              v-model="interlocutor.first_name"
              disabled
            >
          </div>

          <div class="contract-draft__input-box">
            <label class="contract-draft__input-label">Last Name</label>
            <input
              type="text"
              class="contract-draft__input"
              placeholder="Example: Jones"
              v-model="interlocutor.last_name"
              disabled
            >
          </div>
        </template>
        <template v-if="interlocutor.type === 'legal'">

          <div class="contract-draft__input-box">
            <label class="contract-draft__input-label">Company Name</label>
            <input
              type="text"
              class="contract-draft__input"
              placeholder="Example: Erenovate LLC"
              v-model="interlocutor.company_name"
              disabled
            >
          </div>

          <div class="contract-draft__input-box">
            <label class="contract-draft__input-label">Name of Representative</label>
            <input
              type="text"
              class="contract-draft__input"
              placeholder="Example: John Doe"
              v-model="interlocutor.representative_name"
              disabled
            >
          </div>
        </template>

        <div class="contract-draft__sep-title">Address</div>

        <div class="contract-draft__input-box">
          <label class="contract-draft__input-label">Address Line 1</label>
          <input
            type="text"
            class="contract-draft__input"
            placeholder="Example: 1 Main St. SE"
            v-model="interlocutor.address_1"
            disabled
          >
        </div>

        <div class="contract-draft__input-box">
          <label class="contract-draft__input-label">Address Line 2</label>
          <input
            type="text"
            class="contract-draft__input"
            placeholder="Example: App. 123"
            v-model="interlocutor.address_2"
            disabled
          >
        </div>

        <div class="contract-draft__input-box">
          <label class="contract-draft__input-label">City</label>
          <input
            type="text"
            class="contract-draft__input"
            placeholder="Example: Toronto"
            v-model="interlocutor.city"
            disabled
          >
        </div>

        <div class="contract-draft__input-box">
          <label class="contract-draft__input-label">Province</label>
          <input type="text"
                 class="contract-draft__input"
                 placeholder="Example: Ontario"
                 v-model="interlocutor.province"
                 disabled>
        </div>

        <div class="contract-draft__input-box">
          <label class="contract-draft__input-label">Postal Code</label>
          <input
            type="text"
            class="contract-draft__input"
            placeholder="A1A 1A1"
            v-model="interlocutor.postal_code"
            disabled
          >
        </div>
      </div>
      <div class="editing-btn-row_center" v-if="!contract_status !== 'signed'">
        <button
          class="main-btn main-btn_border-blue"
          v-if="interlocutor.status === 'pending'"
          @click="confirmInterlocutorData"
        >
          I CONFIRM USER INFO
        </button>
        <button
          class="main-btn main-btn_border-gray"
          :disabled="contract_status === 'signed' || contract_status === 'finished'"
          v-if="interlocutor.status === 'confirmed'"
          @click="unconfirmInterlocutorData"
        >
          CONFIRMED
        </button>
      </div>
    </template>
  </div>
</template>
<script>
  import {mapGetters} from 'vuex'
  import {confirmDraftInfoUser, unConfirmDraftInfoUser} from "@/api/user";

  export default {
    props: [
      'interlocutor',
      'contract_status'
    ],
    methods: {
      confirmInterlocutorData() {

        // api.post('user.confirmInterlocutorInfo', {user_info_id: this.interlocutor.id})
        confirmDraftInfoUser(this.interlocutor.id).then(response => {
          this.interlocutor.status = 'confirmed'
        })
      },
      unconfirmInterlocutorData() {
        // api.post('user.unconfirmInterlocutorInfo', {user_info_id: this.interlocutor.id})
        unConfirmDraftInfoUser(this.interlocutor.id).then(response => {

          this.interlocutor.status = 'pending'
        })
      },
    },
    computed: {
      ...mapGetters(["user", "contract"]),
      user_contract_text() {
        if (this.user.role === 'homeowner') {
          return 'Contractor Information'
        } else {
          return 'Client Information'
        }
      },
      new_draft_pending() {
        if (this.user.role === 'homeowner') {
          return 'Waiting for Bonded Pro to confirm their details. You can also validate the details once they are provided.'
        } else {
          return 'Waiting for client to confirm their details. You can also validate the details once they are provided.'
        }
      },
      log() {
        if (this.interlocutor.user.userId) console.log(this.interlocutor.user.userId);

        return true;
      }
    },
  }
</script>
