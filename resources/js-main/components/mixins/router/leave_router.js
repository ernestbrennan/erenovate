export default {
  beforeRouteLeave(to, from, next) {
    this.$store.state.leave_router = true;
    next(false);
  }
}
