export default {
  methods: {
    validateContract(modal) {
      let valConatract = false;
      if (modal === 'dialogAcceptDraft' && this.contract.interlocutor_info.status !== 'confirmed') {
        if (this.contract.interlocutor_info.status === 'new') {
          this.$store.commit('set', {
            type: 'errorAlert',
            data: {type: 'info', text: 'Wait for User Info'}
          })
          
        }
        if (this.contract.interlocutor_info.status === 'pending') {
          this.$store.commit('set', {
            type: 'errorAlert',
            data: {type: 'error', text: 'Confirm User Info'}
          })
        }
        return
      }
      this.$store.commit('validateDropZone');
      if (this.$store.state.dropZoneValidMap.valid === false) {
        this.$store.commit('set', {
          type: 'errorAlert',
          data: {type: 'error', text: 'Please, submit files with a comment and click [Upoad Attachment]'}
        });
        return false;
      }
      
      this.$validator.validateAll().then((result) => {
        if (result) {
          if (this.$store.state.dropZoneContract.valid === false) {
            this.$store.commit('set', {
              type: 'errorAlert',
              data: {type: 'error', text: 'Please submit signed contract'}
            });
            var container = $('.contract-comp__body'),
              scrollTo = $('.contract-comp__body #signed_dropzone');
            if (typeof scrollTo !== 'undefined') {
              container.animate({
                scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop() - 100
              }, 1000);
            }
            return false;
            valConatract = true
          } else {
            this[modal] = true
            valConatract = true
          }
        }
        if (!result) {
          
          $('.contract-draft__slide-main-box').slideDown();
          $('.contract-draft__curret').removeClass('active');
          
          valConatract = false
          if (!valConatract) {
            setTimeout(() => {
              if (!valConatract) {
                var container = $('.contract-comp__body'),
                  scrollTo = $('.contract-comp__body .scroll__invalid-input').eq(0);
                if (typeof scrollTo !== 'undefined') {
                  container.animate({
                    scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop() - 200
                  }, 1000);
                }
              }
            }, 400)
          }
        }
      });
    },
  }
};
