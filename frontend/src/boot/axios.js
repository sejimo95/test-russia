import { boot } from 'quasar/wrappers'
import axios from 'axios'

// Be careful when using SSR for cross-request state pollution
// due to creating a Singleton instance here;
// If any client changes this (global) instance, it might be a
// good idea to move this instance creation inside of the
// "export default () => {}" function below (which runs individually
// for each client)
const api = axios.create({ baseURL: 'https://api.example.com' })
axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.token

export default boot(({ app, router, store }) => {
  // for use inside Vue files (Options API) through this.$axios and this.$api

  // redirect to login
  axios.interceptors.response.use((response) => response, (error) => {
    if (error.response.status === 401) {
      store.user = []
      localStorage.token = null
      axios.defaults.headers.common['Authorization'] = ''
      router.push('/login');
    } else if (error.response.status === 500) {
      alert('Connection Failed!')
    } else {
      // return Error object with Promise
      return Promise.reject(error);
    }

  });

  app.config.globalProperties.$axios = axios
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file

  app.config.globalProperties.$api = api
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API
})

export { api }
