export const ContainerHeight = {
    data() {
      return {
          windowHeight: false,
      }
    },
    methods: {
        containerHeight() {
            if (typeof this.contentHeight === 'undefined' || this.contentHeight === false ) {
                let width = window.innerWidth;
                let height = this.$store.state.containerHeight;
                let value = height - 65;
                if (width >= 992) {
                    this.windowHeight = value + 'px'
                    $('.contract-comp').removeAttr("style");
                } else {
                    this.windowHeight = height - 75 + 'px'
                    $('.contract-comp').css('padding-top','75px');
                }
            } else if(typeof this.contentHeight === 'number') {
                this.windowHeight = this.contentHeight + 'px'
            }
        },

    },
    created(){
        this.containerHeight()
        window.addEventListener('resize', this.containerHeight)
    },
    mounted(){
        this.containerHeight()
    },
};
