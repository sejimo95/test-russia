export default ({app, store}) => {
  app.mixin({
    methods: {
      notify (message) {
        this.$q.notify({
          message: message,
          color: 'primary'
        })
      }
    }
  });
};
