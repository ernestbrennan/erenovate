export default {
    getInvoices(context, guarantee_project_id) {

        return new Promise((resolve) => {

            api
                .get('invoice.getList', {
                    params: {guarantee_project_id: guarantee_project_id}
                })
                .then(response => {

                    context.commit('set', {type: 'invoices', data: response.data.invoices});
                    context.commit('set', {type: 'timeline', data: response.data.timeline});
                    context.commit('set', {type: 'has_current_invoice', data: response.data.has_current_invoice});
                    context.commit('set', {type: 'guarantee_project', data: response.data.guarantee_project});

                    resolve(response);
                });
        })
    },
    getInvoiceNew(context, guarantee_project_id) {

        return new Promise((resolve) => {

            api
                .get('invoice.getNew', {
                    params: {guarantee_project_id: guarantee_project_id}
                })
                .then(response => {

                    context.commit('set', {type: 'invoice', data: response.data.invoice});
                    context.commit('set', {type: 'timeline', data: response.data.timeline});
                    context.commit('set', {type: 'guarantee_project', data: response.data.guarantee_project});

                    resolve(response);
                });
        })
    },
    getCurrentInvoice(context, guarantee_project_id) {

        return new Promise((resolve) => {

            api
                .get('invoice.getCurrent', {
                    params: {guarantee_project_id: guarantee_project_id}
                })
                .then(response => {

                    context.commit('set', {type: 'invoice', data: response.data.invoice});
                    context.commit('set', {type: 'timeline', data: response.data.timeline});
                    context.commit('set', {type: 'guarantee_project', data: response.data.guarantee_project});
                    
                    resolve(response);
                })
                .catch(error => {

                    context.commit('set', {type: 'invoice', data: null});

                    resolve()
                });
        })
    },
    getHistoryInvoice(context, invoice_id) {

        return new Promise((resolve) => {

            api
                .get('invoice.getHistory', {
                    params: {invoice_id: invoice_id}
                })
                .then(response => {

                    context.commit('set', {type: 'invoice', data: response.data.invoice});
                    context.commit('set', {type: 'timeline', data: response.data.timeline});
                    context.commit('set', {type: 'guarantee_project', data: response.data.guarantee_project});

                    resolve(response);
                });
        })
    },

    calculateInvoiceSummary(context) {
        context.commit('updateInvoiceSummary', 'materials_total');
        context.commit('updateInvoiceSummary', 'works_total');
    },
}