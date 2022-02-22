export const resizeTextarea = {
  methods: {
    resizeTextarea(event) {
      event.target.style.height = 50 + 'px';
      event.target.style.height = (event.target.scrollHeight) + 'px';
    },
    
  }
};
export const resizeTextareaReadOnly = {
  methods: {
    textareaDisabledResize(model) {
      if (model === undefined || model === null) {
        return false
      } else {
        let culculateFun = () => {
          const arrayString = model.split('\n');
          if (typeof this.$refs.textareaDesc === 'undefined') {
            return false
          }
          const stringW = this.$refs.textareaDesc.offsetWidth || [];
          const line = arrayString.length;
          let val = 50, overline = 0;
          arrayString.map(function (val) {
            let lenString = val.length * 8.5;
            if (lenString >= stringW) {
              overline = overline + (lenString / (stringW - 40));
            }
          });
          
          val = (line + overline) * 23 + 38; // old  16
          this.$refs.textareaDesc.setAttribute('style', 'height: ' + val + 'px');
        };
        culculateFun();
        window.addEventListener('resize', culculateFun);
        
      }
    },
    
  },
  
};
export const nextTickResize = {
  methods: {
    resizeTextarea(event) {
      event.target.style.height = 50 + 'px';
      event.target.style.height = (event.target.scrollHeight) + 4 + 'px';
    },
  },
  mounted() {
    this.$nextTick(() => {
      this.$refs.textareaDesc.setAttribute('style', 'height:' + (this.$refs.textareaDesc.scrollHeight) + 'px;overflow-y:hidden;')
    });
    this.$refs.textareaDesc.addEventListener('input', this.resizeTextarea)
  },
  beforeDestroy() {
    this.$refs.textareaDesc.removeEventListener('input', this.resizeTextarea)
  }
};
export const editResizeTextarea = {
  methods: {
    textareaEditResize(model, ref = 'textareaDesc') {
      
      if (model === undefined || model === null) {
        return false
      } else {
        let culculateFun = () => {
          const arrayString = model.split('\n');
          if (typeof this.$refs[ref] === 'undefined') {
            return false
          }
          const stringW = this.$refs[ref].offsetWidth || [];
          const line = arrayString.length;
          let val = 50, overline = 0;
          arrayString.map(function (val) {
            let lenString = val.length * 8.5;
            if (lenString >= stringW) {
              overline = overline + (lenString / (stringW - 40));
            }
          });
          val = (line + overline) * 23 + 32; // old  16
          this.$refs[ref].setAttribute('style', 'height: ' + val + 'px');
        };
        culculateFun();
        window.addEventListener('resize', culculateFun);
        
      }
    },
  },
  mounted() {
  },
};
