export default {
  data() {
    return {}
  },
  computed: {
    getTour() {
      if (window.innerWidth >= 1200) {
        return true
      }
      return false
    },
  },
}
