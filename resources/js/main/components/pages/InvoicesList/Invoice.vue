<template>
    <div class="contract-invoice" @click="toInvoiceMobile(invoice.id)">
        <div class="contract-invoice__info">
            <div class="contract-invoice__info-name">
                {{invoice.title}}
            </div>
            <div class="contract-invoice__info-date">
                {{invoice.created_at}}
            </div>
        </div>
        <div class="contract-invoice__label" :class="status_class">
            {{text_status}}
        </div>
        <v-spacer></v-spacer>
        <div class="contract-invoice__price">
            ${{invoice.total_price}}
        </div>
        <button class="main-btn_border-blue-deep main-btn contract-invoice__btn" @click="toInvoice(invoice.id)">
            VIEW
        </button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                status: {
                    pending: 'pending',
                    confirmed: 'confirmed',
                    unverified: 'unverified',
                    rejected: 'rejected',
                    completed: 'completed',
                }
            }
        },
        props: {
            invoice: Object
        },
        computed: {
            text_status() {
                let status = this.invoice.status;
                if (status === this.status.pending) return 'Waiting';
                if (status === this.status.confirmed) return 'Confirmed';
                if (status === this.status.unverified) return 'Declined';
                if (status === this.status.rejected) return 'Declined';
                if (status === this.status.completed) return 'Completed';
            },
            status_class() {
                let status = this.invoice.status;
                if (status === this.status.pending) return 'label-blue';
                if (status === this.status.confirmed) return 'label-blue';
                if (status === this.status.unverified) return 'label-green';
                if (status === this.status.rejected) return 'label-red';
                if (status === this.status.completed) return 'label-green';
            },

        },
        methods: {
            toInvoice(invoice_id) {
                if (this.invoice.status === 'rejected' || this.invoice.status === 'completed' ) {
                    return this.$router.push({
                        name: 'history-invoice',
                        params: {
                            guarantee_project_id: this.$route.params.guarantee_project_id,
                            invoice_id: invoice_id,
                        }
                    });
                }

                this.$router.push({
                    name: 'current-invoice',
                    params: {
                        guarantee_project_id: this.$route.params.guarantee_project_id,
                    }
                });
            },
            toInvoiceMobile(invoice_id) {
                if (window.innerWidth > 768) {
                    return false
                } else {
                    this.toInvoice(invoice_id);
                }
            },
        },
    }
</script>
