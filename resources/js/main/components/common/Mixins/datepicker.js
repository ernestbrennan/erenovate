export const minDate = {
    computed:{
        minDate(){
            let d = new Date(),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [year, month, day].join('-');
        },
    },
    methods: {
        minDateEnd(start_model){
            if(start_model === undefined || start_model === null){
                return this.minDate
            }
            return start_model
        },
    }
}