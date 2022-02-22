export default {
  data() {
    return {
      download_pdf_loading: false,
    }
  },
  methods: {
    exportInvoice() {
      this.download_pdf_loading = true
      var params = {
        invoice: this.invoice,
        invoice_summary: this.$store.state.invoice_summary,
      };
      
      api
        .post(
          'invoice.exportPdf',
          params,
          {responseType: 'blob'})
        .then(response => {
          
          this.download_pdf_loading = false
          let a = document.createElement('a');
          let url = window.URL.createObjectURL(response.data);
          
          a.href = url;
          a.download = decodeURIComponent(response.headers['file-name']);
          a.click();
          
          window.URL.revokeObjectURL(url);
        });
      
    },
  },
  
};
