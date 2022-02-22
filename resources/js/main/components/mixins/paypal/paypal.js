import config from '../../../config';

export const paypal_mixin = {
    data() {
        return {
            client_id: config.paypal_client_id,
            currency: 'CAD',
            prices: {
                '500': 8,
                '1,000.00': 7,
                '2,500.00': 5.5,
                '5,000.00': 5,
                '150,000.00': 3,
                '250,000.00': 2.5,
            }
        }
    },
    created() {
        let script = document.createElement('script');

        script.type = 'text/javascript';
        script.async = true;
        script.id = 'paypal_script';
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
        },
        initPayPal() {
            console.log(this.getPaymentDescription());
            paypal.Buttons({
                style: {
                    layout: 'horizontal',
                    color: 'blue',
                    shape: 'rect',
                    size: 'responsive',
                    height: 45,
                    tagline: false,
                },
                commit: true,
                createOrder: (data, actions) => {
                    this.$store.state.global_loader = true;

                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: this.getFee(this.price)
                            },
                            description: this.getPaymentDescription(),
                            details: this.getPaymentDescription(),
                        }]
                    });
                },
                onCancel:  (data) => {
                    this.$store.state.global_loader = false;
                },
                onApprove: (data, actions) => {
                    return actions.order.capture().then(details => {

                        this.approvePayment(data, details)
                    });
                },
                onError:  (err) => {
                    this.$store.state.global_loader = false;

                    if (this.$route.name === 'current-draft' || this.$route.name === 'summary') {
                        this.$store.commit('set', {
                            type: 'errorAlert', data: {
                                type: 'error',
                                text:'Something went wrong. Please, check your balance or contact PayPal support for further assistances'
                            }
                        });
                    }
                }
            }).render('#paypal-button-container');
        },
    },
    beforeDestroy(){
        $('#paypal_script').remove();
    }
};
