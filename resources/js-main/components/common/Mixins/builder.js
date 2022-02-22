export const ContainerHeight = {
  data() {
    return {
      windowHeight: false,
    }
  },
  computed: {
    componetConteinerHeight() {
      //@TODO заменить все переменние на компютев свойства
      if (typeof this.contentHeight === 'undefined' || this.contentHeight === false) {
        let width = window.innerWidth;
        let height = this.$store.state.containerHeight;
        let value = height - 65;
        if (width >= 992) {
          $('.contract-comp').removeAttr("style");
          return value + 'px';
        } else {
          $('.contract-comp').css('padding-top', '75px');
          return height - 75 + 'px';
        }
      } else if (typeof this.contentHeight === 'number') {
        return this.contentHeight + 'px'
      }
    },
  },
  methods: {
    initConteinerHeight() {
      const width = window.innerWidth;
      if (typeof this.contentHeight === 'undefined' || this.contentHeight === false) {
        if (width >= 992) {
          $('.contract-comp').removeAttr("style");
        } else {
          $('.contract-comp').css('padding-top', '75px');
        }
      }
    },
    containerHeight() {
      if (typeof this.contentHeight === 'undefined' || this.contentHeight === false) {
        let width = window.innerWidth;
        let height = this.$store.state.containerHeight;
        let value = height - 65;
        if (width >= 992) {
          this.windowHeight = value + 'px';
          $('.contract-comp').removeAttr("style");
        } else {
          this.windowHeight = height - 75 + 'px';
          $('.contract-comp').css('padding-top', '75px');
        }
      } else if (typeof this.contentHeight === 'number') {
        this.windowHeight = this.contentHeight + 'px'
      }
    },
    
  },
  mounted() {
    this.initConteinerHeight();
  },
};
