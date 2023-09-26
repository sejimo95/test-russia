<template>
  <q-page class="flex flex-center">
    <q-card style="width: 500px; max-width: 100%;">
      <q-card-section>
        <div class="row q-col-gutter-sm">
          <div class="col col-12 title">
            <q-img src="~assets/logo.png" style="height: 150px;" class="q-mb-md" fit="contain"/>
          </div>
          <div class="col col-12">
            <div>E-mail</div>
            <q-input dense filled v-model="email" />
          </div>
          <div class="col col-12">
            <div>Password</div>
            <q-input dense filled v-model="password" type="password" />
          </div>
          <div class="col col-12">
            <q-btn @click="login" color="primary" class="full-width q-mt-sm" label="Login" />
          </div>
        </div>
      </q-card-section>

      <q-inner-loading :showing="loading">
        <q-spinner color="primary" />
      </q-inner-loading>
    </q-card>
  </q-page>
</template>

<script>
export default {
  name: 'LoginPage',
  data () {
    return {
      loading: false,
      email: '',
      password: ''
    }
  },
  mounted() {
    this.checkLogin()
  },
  methods: {
    checkLogin () {
      const app = this
      app.loading = true

      app.$axios.post(app.$s.api + 'api/v1/auth/is-login')
        .then(function (response) {
            app.$router.push({ path: '/panel/deals' })
        })
        .catch(function (error) {
          localStorage.removeItem('token')
          app.$s.user = []
          app.$axios.defaults.headers.common['Authorization'] = ''
        })
        .finally(function () {
          app.loading = false
        })
    },
    login() {
      const app = this
      app.loading = true

      const data = new FormData()
      data.append('data', JSON.stringify({
        email: app.email,
        password: app.password
      }))

      app.$axios.post(app.$s.api + 'api/v1/auth/login', data)
        .then(function (response) {
          localStorage.token = response.data.token
          app.$s.user = response.data.user
          app.$axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.token
          app.$router.push('/panel/deals')
        })
        .catch(function (error) {
          app.notify(error.response.data.message)
        })
        .finally(function () {
          app.loading = false
        })
    }
  }
}
</script>

<style scoped>
.title {
  text-align: center;
  padding: 10px 0;
}
</style>
