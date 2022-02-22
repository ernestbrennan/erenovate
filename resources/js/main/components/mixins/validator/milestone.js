export default {

    methods: {
        validate(modal){
            let valid = false;
            if ( this.$store.state.dropZoneValidMap.valid === false) {
                this.$store.commit('set', {
                    type: 'errorAlert',
                    data: {type: 'error', text: 'Please, submit files with a comment and click [Upoad Attachment]'}
                });
                return false;
            }
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.showDialog(modal)
                    valid = true
                }
                if (!result) {

                    $('.contract-draft__slide-main-box').slideDown();
                    $('.contract-draft__curret').removeClass('active');
                    valid = false

                    setTimeout(() => {
                        if (!valid) {
                            var container = $('.contract-comp__body'),
                                scrollTo = $('.contract-comp__body .scroll__invalid-input').eq(0);
                            if (typeof scrollTo !== 'undefined') {
                                container.animate({
                                    scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop() - 200
                                }, 1000);
                            } else {
                                this.$store.commit('set', {
                                    type: 'errorAlert',
                                    data: {type: 'error', text: 'Error!scrollTo: ' + scrollTo}
                                })
                            }
                        }

                    }, 500)
                }
            });
        },

    },

};
