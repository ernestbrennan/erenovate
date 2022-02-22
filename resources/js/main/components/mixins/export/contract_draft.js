export default {
    data() {
        return {
            download_pdf_loading: false,
        }
    },
    methods: {
        exportContractDraft() {
            this.download_pdf_loading = true;

            var params = {
                contract_draft: this.contract_draft,
                contract: this.contract
            };

            api
                .post(
                    'contract-draft.exportPdf',
                    params,
                    {responseType: 'blob'})
                .then(response => {

                    let a = document.createElement('a');
                    let url = window.URL.createObjectURL(response.data);

                    a.href = url;
                    a.download = decodeURIComponent(response.headers['file-name']);
                    a.click();

                    window.URL.revokeObjectURL(url);

                    this.download_pdf_loading = false;
                });

        },
    },

};
