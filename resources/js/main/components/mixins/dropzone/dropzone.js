export default {

    methods: {
        downloadWithLoading(file_id){

            let a = document.createElement('a');
            a.href = '/api/file.find?id=' + file_id;
            a.click();


            // if(this.loading === true) { return false }
            // this.loading = true
            // api({
            //     method: 'post',
            //     url: 'file.download',
            //     data: {
            //         file_id: file_id
            //     },
            //     responseType: 'blob'
            // })
            //     .then(response => {

                    // let a = document.createElement('a');
                    // let url = window.URL.createObjectURL(response.data);
                    // a.href = url;
                    // a.download = decodeURIComponent(response.headers['file-name']);
                    // a.click();
                    // window.URL.revokeObjectURL(url);
                    // const timer = setTimeout(() => {
                    //     this.loading = false
                    //     clearTimeout(timer)
                    // }, 2000)
                // });

        },
        download(file_id) {
            let a = document.createElement('a');
            a.href = '/api/file.find?id=' + file_id;
            a.click();

            // api({
            //     method: 'post',
            //     url: 'file.download',
            //     data: {
            //         file_id: file_id
            //     },
            //     responseType: 'blob'
            // })
            //     .then(response => {
            //
            //         let a = document.createElement('a');
            //         let url = window.URL.createObjectURL(response.data);
            //         a.href = url;
            //         a.download = decodeURIComponent(response.headers['file-name']);
            //         a.click();
            //         window.URL.revokeObjectURL(url);
            //     });
        },
        formatSize(bytes) {

            if (bytes >= 1073741824) {

                bytes = "<strong>" + (bytes / 1073741824).toFixed(2) + "</strong> GB";

            } else if (bytes >= 1048576) {

                bytes = "<strong>" + (bytes / 1048576).toFixed(2) + "</strong> MB";

            } else if (bytes >= 1024) {

                bytes = "<strong>" + (bytes / 1024).toFixed(2) + "</strong> KB";
            }

            return bytes;
        },
    },

};
