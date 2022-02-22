export const paypal_mixin = {
    data() {
        return {
            client_id: 'AQT3HTLL2RMF_P69XWtLCO55CTCVkz6w9H8d1t8L0MtkeWuw2JUG1YXJA8bREvEfr5fR_CzsZzXQh6rp',
            currency: 'CAD',
            env: 'sandbox',
            prices: {
                '500': 8,
                '1,000.00': 7,
                '2,500.00': 5.5,
                '5,000.00': 4.5,
                '10,000.00': 3,
                '45,000.00': 2,
                '75,000.00': 1.75,
                '100,000.00': 1.5,
            }
        }
    },
    created() {
        let script = document.createElement('script');

        script.type = 'text/javascript';
        script.async = true;
        script.src = `https://www.paypal.com/sdk/js?client-id=${this.client_id}&currency=${this.currency}`;
        script.addEventListener('load', this.initPayPal);

        document.head.appendChild(script);
    },
    mounted(){
        this.$refs.paypal.style.height = 45 + 'px';
    },
    methods: {

        getFee(amount) {
            let fee = 0;

            for(var price in this.prices) {
                if (amount <= Number(price.replace(",", ""))) {

                    fee = amount * this.prices[price] / 100;
                    break;
                }
            }

            if (!fee) {
                fee = amount * 1.5 / 100;
            }

            return fee.toFixed(2);
        }
    },
};